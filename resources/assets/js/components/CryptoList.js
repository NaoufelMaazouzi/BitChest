import React, { useEffect, useState } from 'react';
import MaterialTable from "material-table";
import { Link } from 'react-router-dom';
import { makeStyles, withStyles } from '@material-ui/core/styles';
import { Button, CircularProgress } from '@material-ui/core';
import CoinGecko from 'coingecko-api';
const CoinGeckoClient = new CoinGecko();
import { green } from '@material-ui/core/colors';
import Swal from 'sweetalert2';
import { useSnackbar } from 'notistack';

const useStyles = makeStyles(() => ({
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
    container: {
        marginTop: '100px'
      },
}));

const ColorButton = withStyles(() => ({
    root: {
      color: 'white',
      backgroundColor: green[500],
      '&:hover': {
        backgroundColor: green[700],
      },
    },
  }))(Button);

  const generateDateToday = () => {
    var d = new Date()
    var year = d.getFullYear();
    var month = ("0" + (d.getMonth() + 1)).slice(-2);
    var day = ("0" + d.getDate()).slice(-2);
    var hour = ("0" + d.getHours()).slice(-2);
    var minutes = ("0" + d.getMinutes()).slice(-2);
    var seconds = ("0" + d.getSeconds()).slice(-2);
    return year + "-" + month + "-" + day + " "+ hour + ":" + minutes + ":" + seconds;
}

export default function CryptoList(props) {
    const [cryptos, setCryptos] = useState([]);
    const [user, setUser] = useState({});
    const classes = useStyles();
    const { enqueueSnackbar } = useSnackbar();
    const cryptosBrief = ['bitcoin', 'ethereum','ripple','bitcoin-cash','cardano','litecoin','nem','stellar','iota','dash'];

    useEffect(async () => {
        let cryptosTest = await CoinGeckoClient.coins.markets({vs_currency: 'eur'});
        setCryptos(cryptosTest.data.filter(e => cryptosBrief.includes(e.id)));
        await axios.get(`/api/user/${props.userId}`)
        .then(result => {
           setUser(result.data);
        });
    }, []);

    const handlePurchase = (value) => {
        const {id, current_price, image} = value;
        const cryptoName = id.charAt(0).toUpperCase() + id.slice(1);
        const inputValue = 5;
        const inputStep = 0.01;

        Swal.fire({
            title: `Combien de ${cryptoName} voulez-vous acheter au cours de ${current_price.toFixed(2)}€ ?`,
            html: `
                <input
                type="number"
                value="${inputValue}"
                step="${inputStep}"
                class="swal2-input"
                id="range-value">
                <p id="price">(${(inputValue * current_price).toFixed(2)}€)</p>`,
            input: 'range',
            inputValue,
            showCancelButton: true,
            inputAttributes: {
                min: 0,
                max: 10000,
                step: inputStep
            },
            didOpen: () => {
                const inputRange = Swal.getInput();
                const inputNumber = Swal.getHtmlContainer().querySelector('#range-value');
                const htmlPrice = Swal.getHtmlContainer().querySelector('#price');
            
                // remove default output
                inputRange.nextElementSibling.style.display = 'none';
                inputRange.style.width = '100%';
            
                // sync input[type=number] with input[type=range]
                inputRange.addEventListener('input', () => {
                inputNumber.value = inputRange.value;
                htmlPrice.innerHTML = `(Vous dépenserez ${(inputRange.value * current_price).toFixed(2)}€)`;
                })
            
                // sync input[type=range] with input[type=number]
                inputNumber.addEventListener('change', () => {
                inputRange.value = inputNumber.value;
                htmlPrice.innerHTML = `(Vous dépenserez ${(inputNumber.value * current_price).toFixed(2)}€)`;
                })
            }
            }).then(async (result) => {
                    if(parseFloat(user.solde) < parseFloat(result.value * current_price)) {
                        return enqueueSnackbar(`Vous n\'avez pas assez d\'argent dans votre solde, Votre solde est de ${user.solde}€`, { variant: 'error' });
                    }
                    if (result.value === "0") {
                        return enqueueSnackbar('Veuillez entrer un nombre au dessus de 0', { variant: 'error' });
                    }
                    if(result.isConfirmed){
                            const date = generateDateToday();
                            if (!id || !current_price) {
                                return enqueueSnackbar('Une erreur s\'est produite', { variant: 'error' });
                            }
                            await axios.post(`/api/purchase`, {
                                user_id: props.userId,
                                crypto: id.charAt(0).toUpperCase() + id.slice(1),
                                price: parseFloat(current_price),
                                amount: parseFloat(result.value),
                                totalValue: parseFloat(result.value * current_price),
                                imageUrl: image,
                                date
                            })
                            .then(() => {
                                return enqueueSnackbar(`${cryptoName} acheté !`, { variant: 'success' });
                            })
                            .catch(() => enqueueSnackbar('Une erreur s\'est produite', { variant: 'error' }))

                            const newUserSolde = {
                                ...user,
                                solde: parseFloat(user.solde - (result.value * current_price))
                            }
                            await axios.patch(`/api/user/${props.userId}`, newUserSolde)
                            .then(() => {
                                setUser(newUserSolde)
                                enqueueSnackbar(`${(result.value * current_price).toFixed(2)}€ ont été retirés de votre solde`, { variant: 'success' });
                            })
                            .catch(() => enqueueSnackbar('Une erreur s\'est produite ', { variant: 'error' }))
                    }
                })
            }

    return (
        !cryptos.length ?
        <div className={classes.div}>
            <CircularProgress />
        </div>  : 
        <div className={classes.container}>
            <MaterialTable
                title="Liste des cryptos"
                columns={[
                    { title: 'Nom', field: 'id', render: rowData => <div><img height="20rem" width="20rem" style={{marginRight: '10px'}} src={rowData.image} />{rowData.id.charAt(0).toUpperCase() + rowData.id.slice(1)}</div>},
                    { title: 'Prix', field: 'current_price', render: rowData => `${rowData.current_price}€` },
                    { title: 'Courbe',
                    render: (rowData) => 
                    <Link to={`chart/${rowData.id}`}>
                        <Button variant="contained" color="primary">Voir la courbe</Button>
                    </Link>
                    },
                    { title: 'Acheter',
                    render: (rowData) => 
                        <ColorButton variant="contained" onClick={() => handlePurchase(rowData)}>Acheter</ColorButton>
                    }
                ]}
                data={cryptos}
                options={{
                    pageSize:10,
            }}
                // editable={{
                //     onRowAdd: newData => addUser(newData),
                //     onRowUpdate: (newData, oldData) => updateUser(newData, oldData),
                //     onRowDelete: oldData => deleteUser(oldData)
                //   }}
                // localization={{ body: { editRow: { deleteText: 'Valider la suppression ?' } } }}
            />
        </div>
    );
}