import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Switch } from 'react-router-dom';
import Login from './Login';
import SideDrawerComponent from './SideDrawer';
import Users from './Users';
import { SnackbarProvider } from 'notistack';
import Chart from './Chart';
import CryptoList from './CryptoList';
import Wallet from './Wallet';
import Register from './Register';
import PersonalData from './PersonalData';
import PrivateRoute from '../routes/PrivateRoute';
import PublicRoute from '../routes/PublicRoute';
import ProtectedRoute from '../routes/ProtectedRoute';

const App = (props) => {
        const userLoggedin = JSON.parse(props.userloggedin);
        const userId = JSON.parse(props.userid);

        return (
            <Router>
                <SnackbarProvider maxSnack={3}>
                    <div className="container">
                        <SideDrawerComponent userLoggedin={userLoggedin} />
                        <Switch>
                            <PublicRoute path="/login" component={() => <Login userLoggedin={userLoggedin}/>} />
                            <PublicRoute path="/register" component={() => <Register/>} />
                            <PrivateRoute path="/admin/users" userLoggedin={userLoggedin} component={() => <Users/>} />
                            <ProtectedRoute path="/chart/:name" userLoggedin={userLoggedin} component={(props) => <Chart {...props} />  } />
                            <ProtectedRoute path="/cryptoList" userLoggedin={userLoggedin} component={() => <CryptoList userId={userId.userId}/>} />
                            <ProtectedRoute exact path="/" userLoggedin={userLoggedin} component={() => <Wallet userId={userId.userId} />} />
                            <ProtectedRoute path="/PersonalData" userLoggedin={userLoggedin} component={() => <PersonalData userId={userId.userId}/>} />
                        </Switch>
                    </div>
                </SnackbarProvider>
            </Router>
        );
}

const element = document.getElementById('rootReact')

if (element) {
    ReactDOM.render(<App {...element.dataset} />, document.getElementById('rootReact'));
}
