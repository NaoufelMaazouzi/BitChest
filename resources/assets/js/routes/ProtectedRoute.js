
import React from "react";
import { Route, Redirect } from "react-router-dom";

function ProtectedRoute({ component: Component, userLoggedin, ...rest }) {
    return (
        <Route
            {...rest}
            render={props =>
                userLoggedin && ['user', 'admin'].includes(userLoggedin.user) ? (
                    <Component {...props} />
                ) :  (
                <Redirect to="/login" />
                )
            }
        />
    );
}

export default ProtectedRoute;