import React, {useEffect, useState} from 'react';
import { makeStyles, withStyles } from '@material-ui/core/styles';
import axios from 'axios';
import { useSnackbar } from 'notistack';
import {
    CardHeader,
    Grid,
    Divider,
    Typography,
    CardContent,
    Card,
    List,
    ListItem,
    ListItemText,
    ListItemSecondaryAction,
    Button,
    CircularProgress
} from '@material-ui/core';
import { green } from '@material-ui/core/colors';
import CoinGecko from 'coingecko-api';
import Swal from 'sweetalert2';
import withReactContent from 'sweetalert2-react-content';
import AnimatedNumber from "animated-number-react";
const MySwal = withReactContent(Swal);
const CoinGeckoClient = new CoinGecko();

const useStyles = makeStyles({
  container: {
    marginTop: '77px'
  },
centeredGrid: {
    display:'flex',
    justifyContent:'center',
  },
  centeredItems: {
    display:'flex',
    justifyContent:'center',
    color: 'white'
  },
  marginItems: {
    display:'flex',
    justifyContent:'center',
    marginLeft: '100px'
  },
  marginItems2: {
    display:'flex',
    justifyContent:'center',
    marginLeft: '45px'
  },
  coursGrid: {
      minHeight: '15vh'
  },
  list: {
      backgroundColor: 'rgba(74, 91, 186, 0.8)'
  },
  div: {
    width: '100px',
    height: '100px',
    position: 'absolute',
    top: 0,
    bottom: 0,
    left: 0,
    right: 0,
    margin: 'auto'
},
});

const ColorButton = withStyles(() => ({
    root: {
      color: 'white',
      backgroundColor: green[500],
      '&:hover': {
        backgroundColor: green[700],
      },
    },
  }))(Button);

export default function Wallet(props) {
    const classes = useStyles();
    const { enqueueSnackbar } = useSnackbar();
    const [solde, setSolde] = useState(0);
    const [purchases, setPurchases] = useState([]);
    const [totalValueCrypto, setTotalValueCrypto] = useState([]);
    const [selectedPurchases, setSelectedPurchases] = useState({});
    const [selectedHisto, setSelectedHisto] = useState({});
    const [cryptos, setCryptos] = useState([]);
    const [selectedIndexPort, setSelectedIndexPort] = useState();
    const [selectedIndexHisto, setSelectedIndexHisto] = useState();
    const [realSolde, setRealSolde] = useState(0);
    const [loading, setLoading] = useState(false);

    const cryptosBrief = ['bitcoin', 'ethereum','ripple','bitcoin-cash','cardano','litecoin','nem','stellar','iota','dash'];

    
    useEffect(async () => {
        doRequest(true);
    }, [])

    const groupBy = (array, key, filter) => {
        let helper = {};
        let groupedCrypto = array.reduce((res, value) => {
            const currentPriceCrypto = filter.filter(e => e.id === value.crypto.toLowerCase())[0].current_price;
            let key = value.crypto;
            if(!helper[key]) {
                helper[key] = {...value, totalValue: currentPriceCrypto * parseFloat(value.amount)};
                res.push(helper[key]);
            } else {
                helper[key].totalValue = parseFloat(helper[key].totalValue) + (currentPriceCrypto * parseFloat(value.amount));
                helper[key].amount = parseFloat(helper[key].amount) + parseFloat(value.amount);
            }

            return res;
        }, []);
        return groupedCrypto;
      };

      const handleClick = async (cryptoName, index) => {
          const selected = purchases.filter(e => e.crypto === cryptoName);
          setSelectedPurchases(selected);
          setSelectedIndexPort(index);
          setSelectedIndexHisto();
          setSelectedHisto({});
        }

      const handleClickHisto = (selected, index) => {
          setSelectedHisto(selected);
          setSelectedIndexHisto(index);
        }

      const plusValue = (selectedHisto) => {
        if(cryptos.length && Object.keys(selectedHisto).length !== 0) {
            const spend = parseFloat(selectedHisto.price) * parseFloat(selectedHisto.amount);
            const currentValue = cryptos.find(e => e.id === selectedHisto.crypto.toLowerCase()).current_price * parseFloat(selectedHisto.amount);
            const plusValue = currentValue - spend;
            if(Math.sign(plusValue) === -1) {
                return {firstText: `perdre ${Math.abs(plusValue).toFixed(2)}€ ?`,
                secondText: `${currentValue.toFixed(2)}€ ajouté à votre solde !`,
                currentValue}; 
            } else if (Math.sign(plusValue) === 1) {
                return {firstText: `gagner ${plusValue.toFixed(2)}€ ?`,
                secondText: `${currentValue.toFixed(2)}€ ajouté à votre solde !`,
                currentValue}; 
            } else {
                return {firstText: 'gagner 0€ ?', secondText: '0€ ajouté à votre solde !', currentValue }; 
            }
        }
        return {}
      }

      const doRequest = async (isFirstRequest) => {
        if (isFirstRequest) {
            setLoading(true);
        }
        let cryptosTest = await CoinGeckoClient.coins.markets({vs_currency: 'eur'});
        setCryptos(cryptosTest.data.filter(e => cryptosBrief.includes(e.id)));
        await axios.get(`/api/purchase/${props.userId}`)
        .then(result => {
            if(result.data.length) {
                setPurchases(result.data);
                const totalValueFiltered = groupBy(result.data, 'crypto', cryptosTest.data.filter(e => cryptosBrief.includes(e.id)));
                setTotalValueCrypto(totalValueFiltered);
                setLoading(false);
                setSolde(totalValueFiltered.reduce((prev, curr) => {
                    return parseFloat(prev) + parseFloat(curr.totalValue)
                }, 0));
                setSelectedPurchases({});
                setSelectedHisto({});
            } else {
                setLoading(false);
                setPurchases([]);
                setSolde(0);
                setTotalValueCrypto([])
                setSelectedPurchases({});
                setSelectedHisto({});
            }
        })
        .catch(err => {
            return enqueueSnackbar('Une erreur s\'est produite', { variant: 'error' });
           });
        await axios.get(`/api/user/${props.userId}`)
        .then(result => {
           setRealSolde(result.data.solde);
        });
      }

      const handleSale = (selectedHisto) => {
        const {firstText, secondText, currentValue} = plusValue(selectedHisto);
        MySwal.fire({
            title: `Etes-vous sûr de vouloir vendre ${selectedHisto.amount} ${selectedHisto.crypto} pour ${currentValue.toFixed(2)}€ et ${firstText}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33', 
            confirmButtonText: 'Oui !',
          }).then((result) => {
              if(result.isConfirmed){
                axios.post(`/api/deletePurchase/${selectedHisto.id}`, {currentValue: currentValue})
                .then(e => {
                    doRequest();
                    enqueueSnackbar(`Crypto vendue, ${secondText}`, { variant: 'success' });
                })
                .catch(() => enqueueSnackbar('Une erreur s\'est produite ', { variant: 'error' }))
              }
          })
    }

    const formatValue = (value) => `${value.toFixed(2)}€`;
    return (
        loading ? 
        <div className={classes.div}>
            <CircularProgress />
        </div>
        : 
        <Grid container spacing={6} className={classes.container}>
            <Grid item xs={6}>
            <Card className={classes.root}>
                <CardHeader title="Mon portefeuille" />
                <Divider />
                <CardContent>
                        <br/>
                        <Grid container spacing={0} direction="column" alignItems="center" justify="center">
                            <Grid container xs={12}>
                                <Grid container xs={6}>
                                    <Grid item xs={12} className={classes.centeredGrid}>
                                        <Typography className={classes.title} variant="h5" component="h2" gutterBottom>
                                            <AnimatedNumber value={realSolde} formatValue={formatValue}/>
                                        </Typography>
                                    </Grid>
                                    <Grid item xs={12} className={classes.centeredGrid}>
                                        <Typography color="textSecondary">
                                        Votre solde réel (cryptos vendues)
                                        </Typography>
                                    </Grid>
                                </Grid>
                                <Grid container xs={6}>
                                    <Grid item xs={12} className={classes.centeredGrid}>
                                        <Typography className={classes.title} variant="h5" component="h2" gutterBottom>
                                            <AnimatedNumber value={solde} formatValue={formatValue}/>
                                        </Typography>
                                    </Grid>
                                    <Grid item xs={12} className={classes.centeredGrid}>
                                        <Typography color="textSecondary">
                                        Argent potentiel (cryptos à vendre)
                                        </Typography>
                                    </Grid>
                                </Grid>
                                
                            </Grid>
                        </Grid>
                        <br/>
                        <br/>
                        <br/>
                        <Divider />
                    <List className={classes.root}>
                        {totalValueCrypto.length ? totalValueCrypto.map((purchase, i) => {
                                return (
                                <div>
                                    <ListItem key={i} button onClick={() =>handleClick(purchase.crypto, i)} selected={selectedIndexPort === i}>
                                        <img height="20rem" width="20rem" src={purchase.imageUrl} style={{marginRight: '10px'}}/>
                                        <ListItemText primary={`${purchase.crypto}`} />
                                        <ListItemSecondaryAction>
                                            <Typography>{purchase.totalValue.toFixed(2)}€</Typography>
                                        </ListItemSecondaryAction>
                                    </ListItem>
                                    <Divider />
                                    <br/>
                                </div>
                                )}
                            ) : 
                                <ListItem >
                                        <Typography>Vous n'avez aucun achat en cours</Typography>
                                </ListItem>
                           }
                    </List>
                </CardContent>
            </Card>
            </Grid>
            <Grid item xs={6}>
                <Grid container spacing={1}>
                    <Grid item xs={6}>
                        <Card className={classes.coursGrid}>
                            <CardContent>
                            <Typography className={classes.title} color="textSecondary" gutterBottom>
                                {selectedPurchases.length ? selectedPurchases[0].crypto : ''}
                            </Typography>
                            <Grid container spacing={0} direction="column" alignItems="center" justify="center">
                                <Grid>
                                    <Typography variant="h5" component="h2">
                                        {Object.keys(selectedHisto).length !== 0 ?
                                            <AnimatedNumber value={selectedHisto.price} formatValue={formatValue}/>
                                            : ''}
                                    </Typography>
                                    <Typography color="textSecondary">
                                    (Cours acheté)
                                    </Typography>
                                </Grid>
                            </Grid>
                            </CardContent>
                        </Card>
                    </Grid>
                    <Grid item xs={6}>
                        <Card className={classes.coursGrid}>
                            <CardContent>
                            <Typography className={classes.title} color="textSecondary" gutterBottom>
                                {selectedPurchases.length ? selectedPurchases[0].crypto : ''}
                            </Typography>
                            <Grid container spacing={0} direction="column" alignItems="center" justify="center">
                                <Grid>
                                    <Typography variant="h5" component="h2">
                                        {selectedPurchases.length && cryptos.length ?
                                            <AnimatedNumber
                                                value={cryptos.find(e => e.id === selectedPurchases[0].crypto.toLowerCase()).current_price}
                                                formatValue={formatValue}
                                            />
                                             : ''}
                                    </Typography>
                                    <Typography color="textSecondary">
                                    (Cours actuel)
                                    </Typography>
                                </Grid>
                            </Grid>
                            </CardContent>
                        </Card>
                    </Grid>
                    {Object.keys(selectedHisto).length ? <Grid item xs={12}>
                        <Card className={classes.root}>
                            <CardContent>
                            <Grid container spacing={0} direction="column" alignItems="center" justify="center">
                                <Grid item xs={12}>
                                    <Typography className={classes.title} color="textSecondary" gutterBottom>
                                        {`Voulez-vous vendre et ${plusValue(selectedHisto).firstText}`}
                                    </Typography>
                                </Grid>
                            </Grid>
                            <br/>
                            <Grid container spacing={0} direction="column" alignItems="center" justify="center">
                                <Grid item xs={12}>
                                    <ColorButton size="large" variant="contained" onClick={() => handleSale(selectedHisto)}>Vendre</ColorButton>
                                </Grid>   
                            </Grid>
                            </CardContent>
                        </Card>
                    </Grid> : ''}
                    <Grid item xs={12}>
                        <Card className={classes.root}>
                        <CardContent>
                        <br/>
                        <Grid container spacing={0} direction="column" alignItems="center" justify="center">
                            <Grid item xs={12}>
                                <Typography variant="h5" component="h2">
                                    Historique
                                </Typography>
                                <br/>
                                <br/>
                                <br/>
                            </Grid>
                        </Grid>
                        <List>
                            <div  className={classes.list}>
                                <ListItem key={'titleColumn'}>
                                    <ListItemText className={classes.centeredItems} primary={'Crypto'} />
                                    <ListItemText className={classes.centeredItems} primary={'Quantité'} />
                                    <ListItemText className={classes.centeredItems} primary={'Date'} />
                                </ListItem>
                                <Divider />
                            </div>
                        </List>
                        {/* <Divider /> */}
                        <List className={classes.root}>
                            {selectedPurchases.length && selectedPurchases.map((selected,i) => {
                                    return (
                                    <div>
                                        <ListItem key={i}  button onClick={() => handleClickHisto(selected, i)} selected={selectedIndexHisto === i}>
                                            <ListItemText className={classes.marginItems2} primary={selected.crypto} />
                                            <ListItemText className={classes.marginItems} primary={selected.amount} />
                                            <ListItemText className={classes.marginItems} primary={selected.date} />
                                        </ListItem>
                                        <Divider />
                                        <br/>
                                    </div>
                                    )}
                                )}
                        </List>
                </CardContent>
                        </Card>
                    </Grid>
                </Grid>
            </Grid>
        </Grid>
    );
}
