import 'regenerator-runtime/runtime';
import React from 'react';

import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { applyMiddleware, compose, createStore } from 'redux';
import createSagaMiddleware from 'redux-saga';

import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';

import AppRoot from './modules/_shared/components/AppRoot';
import authenticationSaga from './modules/login/sagas';
import rootReducer from './reducer';
import theme from './theme';

const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
const sagaMiddleware = createSagaMiddleware();

const store = createStore(
  rootReducer,
  {},
  composeEnhancers(applyMiddleware(sagaMiddleware)),
);

sagaMiddleware.run(authenticationSaga);

ReactDOM.render((
  <Provider store={ store }>
    <MuiThemeProvider muiTheme={ theme }>
      <AppRoot/>
    </MuiThemeProvider>
  </Provider>
), document.querySelector('#application-root'));

