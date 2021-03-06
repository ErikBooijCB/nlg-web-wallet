import 'regenerator-runtime/runtime';
import React from 'react';

import ReactDOM                                  from 'react-dom';
import { Provider }                              from 'react-redux';
import { applyMiddleware, compose, createStore } from 'redux';
import createSagaMiddleware                      from 'redux-saga';

import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';

import scheduleAction        from './scheduleAction';
import AppRoot               from './modules/_shared/components/AppRoot';
import statusBarSaga         from './modules/_shared/saga/statusBar';
import * as statusBarActions from './modules/_shared/actions/statusBar';
import authenticationSaga    from './modules/login/saga';
import * as authenticationActions from './modules/login/actions';
import rootReducer           from './reducer';
import theme                 from './theme';

// eslint-disable-next-line no-underscore-dangle
const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
const sagaMiddleware = createSagaMiddleware();

const store = createStore(
  rootReducer,
  {},
  composeEnhancers(applyMiddleware(sagaMiddleware)),
);

scheduleAction(authenticationActions.checkLoginStatus, 30000, store.dispatch);
scheduleAction(statusBarActions.fetchStatusBarData, 5000, store.dispatch);

sagaMiddleware.run(authenticationSaga);
sagaMiddleware.run(statusBarSaga);

ReactDOM.render(
  (
    <Provider store={ store }>
      <MuiThemeProvider muiTheme={ theme }>
        <AppRoot />
      </MuiThemeProvider>
    </Provider>
  ),
  document.querySelector('#application-root'),
);

