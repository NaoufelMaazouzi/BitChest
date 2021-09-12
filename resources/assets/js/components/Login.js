import React, { useState } from 'react';
import { Link, useHistory, Route, Redirect } from "react-router-dom";
import { useFormik } from 'formik';
import axios from 'axios';
import Alert from '@material-ui/lab/Alert';
import { makeStyles } from '@material-ui/core/styles';
import { useSnackbar } from 'notistack';
import BitchestLogo from '../../images/bitchest_logo.png';

/*Function to validate values from login form*/
const validate = values => {
    let errors = {};

    if (!values.email) {
        errors.email = "Email requis";
    }
    if (!values.password) {
        errors.password = "Mot de passe requis"
    }
    return errors;
}

const useStyles = makeStyles(() => ({
    formSignup: {
        background: '#fff',
        padding: '2em 4em 2em',
        maxWidth: '400px',
        margin: '50px auto 0',
        boxShadow: '0 0 1em rgb(163, 163, 163)',
        borderRadius: '2px',
        marginTop: '100px'
    },
    titleSignup: {
        padding: '10px',
        textAlign: 'center',
        fontSize: '30px',
        color: 'darken(#e5e5e5, 50%)',
        borderBottom: 'solid 1px #e5e5e5',
    },
    divInputSingup: {
        margin: '0 0 2.5em 0',
        position: 'relative',
        display: 'flex',
        justifyContent: 'center'
    },
    input: {
        display: 'block',
        boxSizing: 'border-box',
        width: '100%',
        outline: 'none',
        margin: 0,
    },
    inputSignup: {
        background: '#fff',
        border: '1px solid #dbdbdb',
        fontSize: '1.6em',
        borderRadius: '2px',
        cursor: 'text',
        padding: '0.3em 0',
        '&:focus': {
            background: "#fff",
         },
         '&::placeholder': {
            position: 'absolute',
            left: '8px',
            top: '2px',
            color: '#999',
            fontSize: '16px',
            display: 'inline-block',
            padding: '4px 10px',
            fontWeight: '400',
            backgroundColor: 'rgba(255, 255, 255, 0)',
            }
    },
    submitBtn: {
        background: 'rgba(148, 186, 101, 0.7)',
        boxShadow: '0 3px 0 0 darken(rgba(148, 186, 101, 0.7), 10%)',
        borderRadius: '2px',
        border: 'none',
        color: '#fff',
        cursor: 'pointer',
        display: 'block',
        fontSize: '1.2em',
        lineHeight: '1.6em',
        margin: '2em 0 0',
        padding: '0.3em 0',
        textShadow: '0 1px #68b25b',
        '&:hover': {
                background: 'rgba(148, 175, 101, 1)',
                textShadow: '0 1px 3px darken(rgba(148, 186, 101, 0.7), 30%)',
            }
    },
    errorMessage: {
        color: 'red',
    },
    logo: {
        height: '5rem',
        width: '16rem',
        marginBottom: '10px',
    }, 
    divLogo: {
        display: 'flex',
        justifyContent: 'center',
    }
  }));

export default function Login(props) {
   /*Declare all states*/
   const [isError, setIsError] = useState(false);
   const classes = useStyles();
   const { enqueueSnackbar } = useSnackbar();
   let history = useHistory();

   /*Using react formik to simplify form handling*/
   const formik = useFormik({
       initialValues: {
           email: '',
           password: ''
       },
       /*Post method to login user when submiting form*/
       onSubmit: async (e) => {
           await axios.post('/login', {email: e.email, password: e.password})
           .then(e => {
               history.push('/admin/users');
               history.go();
           })
           .catch(err => {
            if(err.response.status){
                return enqueueSnackbar('Mauvais identifiant / Mot de passe', { variant: 'error' });
               }
           })
       },
       validate
   })

   return (
    <Route
        render={() =>
            props.userLoggedin && ['user', 'admin'].includes(props.userLoggedin.user) ? (
                <Redirect to="/cryptoList" />
            ) :  (
            <form className={classes.formSignup} onSubmit={formik.handleSubmit}>
            {isError && <Alert severity="error">Erreur de conexion. Mauvais nom ou mot de passe</Alert>}
            <h2 className={classes.titleSignup}>Se connecter</h2>
            <div className={classes.divLogo}>
                <img src={BitchestLogo} className={classes.logo}/>
            </div>
            <div className={classes.divInputSingup}>
                <input
                    id="email"
                    name="email"
                    type="text"
                    placeholder="Nom"
                    className={classes.inputSignup}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    value={formik.values.email} />
            </div>
            {formik.touched.email && formik.errors.email && <Alert severity="error">{formik.errors.email}</Alert>}
            <div className={classes.divInputSingup}>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Mot de passe"
                    className={classes.inputSignup}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    value={formik.values.password} />
            </div>
            {formik.touched.password && formik.errors.password && <Alert severity="error">{formik.errors.password}</Alert>}
            <div className={classes.divInputSingup}>
                <p>
                    <input type="submit" value="Me connecter" id="submit" className={classes.submitBtn} />
                </p>
            </div>
            <div className={classes.divInputSingup}>
                <p>
                    <Link to='/register'>
                        Je n'ai pas de compte
                    </Link>
                </p>
            </div>
        </form>
            )
        }
    />
    );
}