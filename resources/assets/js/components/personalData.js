import React, { useEffect, useState } from 'react';
import { useHistory } from "react-router-dom";
import axios from 'axios';
import { makeStyles } from '@material-ui/core/styles';
import { useSnackbar } from 'notistack';
import DeleteIcon from '@material-ui/icons/Delete';
import { Button,
    TextField,
    Grid,
    Divider,
    Card,
    CardContent,
    CardHeader
} from '@material-ui/core';
import withReactContent from 'sweetalert2-react-content';
import Swal from 'sweetalert2';
const MySwal = withReactContent(Swal);

const useStyles = makeStyles(() => ({
    container: {
        marginTop: '100px'
    },
    centeredGrid: {
        display:'flex',
        justifyContent:'center',
    },
    flexendGrid: {
        display:'flex',
        justifyContent:'flex-end',
        marginTop: '20px'
    }
  }));

export default function PersonalData(props) {
   /*Declare all states*/
   const classes = useStyles();
   const history = useHistory();
   const { enqueueSnackbar } = useSnackbar();
   const [user, setUser] = useState({});

   useEffect(async () => {
    await axios.get(`/api/user/${props.userId}`)
    .then(result => {
       setUser(result.data);
    });
   }, [])

   const deleteUser = () => {
    MySwal.fire({
        title: 'Etes-vous sûr de vouloir supprimer votre compte ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33', 
        confirmButtonText: 'Oui !',
      }).then((result) => {
          if(result.isConfirmed){
            axios.delete(`/api/user/${props.userId}`)
            .then(e => {
                history.go();
                enqueueSnackbar('Votre compte a été supprimé', { variant: 'success' });
            })
            .catch(() => enqueueSnackbar('Une erreur s\'est produite ', { variant: 'error' }))
          }
      })
}

const updateUser = async () => {
    await axios.patch(`/api/user/${props.userId}`, user)
    .then(updated => {
        enqueueSnackbar('Vos informations ont été modifiées', { variant: 'success' });
    })
    .catch(() => enqueueSnackbar('Une erreur s\'est produite ', { variant: 'error' }))
}

const handleChange = (name, event) => {
    setUser({...user, [name]: event.target.value})
}

   return (
        <Card className={classes.container}>
            <CardHeader title="Modifier mes données personnelles" />
            <Divider />
            <CardContent>
                <br/>
                <Grid container spacing={0} direction="column" alignItems="center" justify="center">
                    {Object.keys(user).length ? <Grid container xs={12}>
                        <Grid container xs={4}>
                            <Grid item xs={12} className={classes.centeredGrid}>
                                <TextField id="standard-basic" label="Name" type="name" variant="outlined" defaultValue={user.name} onChange={(event) => handleChange('name', event)}/>
                            </Grid>
                        </Grid>
                        <Grid container xs={4}>
                            <Grid item xs={12} className={classes.centeredGrid}>
                                <TextField id="standard-basic" label="Email" type="email" variant="outlined" defaultValue={user.email} onChange={(event) => handleChange('email', event)}/>
                            </Grid>
                        </Grid>
                        <Grid container xs={4}>
                            <Grid item xs={12} className={classes.centeredGrid}>
                                <TextField id="standard-basic" label="Mot de passe" type="password" variant="outlined" onChange={(event) => handleChange('password', event)}/>
                            </Grid>
                        </Grid>
                        <Grid item xs={12} className={classes.flexendGrid}>
                        <Button
                            variant="contained"
                            color="primary"
                            className={classes.button}
                            onClick={updateUser}
                        >
                            Sauvegarder
                        </Button>
                        </Grid>
                    </Grid> : ''}
                </Grid>
                <br/>
                <br/>
                <br/>
                <Divider />
                <br/>
                <br/>
                <Button
                    variant="outlined"
                    color="secondary"
                    className={classes.button}
                    startIcon={<DeleteIcon />}
                    onClick={deleteUser}
                >
                    Supprimer mon compte
                </Button>
            </CardContent>
        </Card>
    );
}