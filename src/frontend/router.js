import React from 'react';

import PropTypes                                  from 'prop-types';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';

import HomeScreen        from './modules/homescreen/component';
import Login             from './modules/login/component/login';
import Logoff            from './modules/login/component/logoff';
import PasswordForgotten from './modules/login/component/password-forgotten';
import NotFound          from './modules/not-found/component';

const AppRouter = ({ loggedIn }) => (
  <Router>
    { loggedIn
      ? (
        <Switch>
          <Route exact path="/" component={ HomeScreen } />
          <Route exact path="/login" component={ Login } />
          <Route exact path="/logoff" component={ Logoff } />
          <Route exact path="/password-forgotten" component={ PasswordForgotten } />
          <Route component={ NotFound } />
        </Switch>
      ) : (
        // Add routes that need to be available to users that are not logged in here:
        <Switch>
          <Route exact path="/password-forgotten" component={ PasswordForgotten } />
          <Route component={ Login } />
        </Switch>
      )
    }
  </Router>
);

AppRouter.propTypes = {
  loggedIn: PropTypes.bool.isRequired,
};

export default AppRouter;
