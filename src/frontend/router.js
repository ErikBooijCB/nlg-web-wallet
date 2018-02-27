import React from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';

import Login from './modules/login/component';

import NotFound from './modules/not-found/component';

export default () => (
  <Router>
    <Switch>
      <Route exact path="/login" component={ Login } />
      <Route component={ NotFound } />
    </Switch>
  </Router>
);
