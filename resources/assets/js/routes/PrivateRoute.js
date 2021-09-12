
import React from "react";
import { Route, Redirect } from "react-router-dom";

function PrivateRoute({ component: Component, userLoggedin, ...rest }) {
    return (
        <Route
            {...rest}
            render={props =>
                userLoggedin && userLoggedin.user === 'admin' ? (
                    <Component {...props} />
                ) :  (
                <Redirect to="/login" />
                )
            }
        />
    );
}

export default PrivateRoute;