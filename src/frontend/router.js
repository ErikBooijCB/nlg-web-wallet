import React from 'react';

import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';

import HomeScreen from './modules/homescreen/component';
import Login      from './modules/login/component/login';
import Logoff     from './modules/login/component/logoff';
import NotFound   from './modules/not-found/component';

const AppRouter = () => (
  <Router>
    <Switch>
      <Route exact path="/" component={ HomeScreen } />
      <Route exact path="/login" component={ Login } />
      <Route exact path="/logoff" component={ Logoff } />
      <Route component={ NotFound } />
    </Switch>
  </Router>
);

export default AppRouter;
