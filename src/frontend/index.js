import 'regenerator-runtime/runtime';
import React from 'react';

import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { applyMiddleware, compose, createStore } from 'redux';
import createSagaMiddleware from 'redux-saga';

import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';

import rootReducer from './reducer';
import Router from './router';
import theme from './theme';
import loginSaga from './modules/login/sagas';

const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
const sagaMiddleware = createSagaMiddleware();

const store = createStore(
  rootReducer,
  {},
  composeEnhancers(applyMiddleware(sagaMiddleware)),
);

ReactDOM.render((
  <Provider store={ store }>
    <MuiThemeProvider muiTheme={ theme }>
      <Router/>
    </MuiThemeProvider>
  </Provider>
), document.querySelector('#application-root'));

sagaMiddleware.run(loginSaga);
