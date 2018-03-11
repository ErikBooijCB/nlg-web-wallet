import { all, call, put, takeLatest } from 'redux-saga/effects';

import * as actions         from '../actions';
import AccessTokenService   from '../../_shared/services/AccessTokenService';
import { doDelete, doPost } from '../../_shared/utilities/requestHelper';

const accessTokenService = new AccessTokenService();

/* eslint-disable consistent-return */
function* handleCheckLoginStatus() {
  try {
    const token = accessTokenService.getAccessToken();

    if (!token) {
      yield put(actions.loginStatusNotLoggedIn());

      return 0;
    }

    const tokenValidity = yield call([ accessTokenService, 'validateToken' ], token);

    if (tokenValidity) {
      yield put(actions.loginStatusLoggedIn());
    } else {
      yield attemptRefreshOfToken();
    }
  } catch (e) {
    yield put(actions.loginStatusNotLoggedIn());
  }
}
/* eslint-enable consistent-return */

function* attemptRefreshOfToken() {
  try {
    const newToken = yield call([ accessTokenService, 'refreshToken' ]);

    if (newToken) {
      yield put(actions.loginStatusLoggedIn());
    } else {
      yield put(actions.loginStatusNotLoggedIn());
    }
  } catch (e) {
    yield put(actions.loginStatusNotLoggedIn());
  }
}

/* eslint-disable consistent-return */
function* handleLogIn(action) {
  const { email, password } = action.payload;

  if (!email || !password) {
    yield put(actions.logInFailed());

    return null;
  }

  try {
    const { status, body } = yield call(doPost, '/api/access-tokens', {
      email,
      password,
    });

    if (status !== 201) {
      yield put(actions.logInFailed());
    } else {
      const { data: { accessToken, refreshToken } } = body;

      yield put(actions.logInSucceeded({ accessToken, refreshToken }));
    }
  } catch (e) {
    yield put(actions.logInFailed());
  }
}
/* eslint-enable consistent-return */

function handleLogInSucceeded(action) {
  const { accessToken, refreshToken } = action.payload;

  accessTokenService.setToken(accessToken, refreshToken);
}

/* eslint-disable consistent-return */
function* handleLogOff() {
  try {
    const accessToken = accessTokenService.getAccessToken();

    const { status } = yield call(doDelete, `/api/access-tokens/${accessToken}`);

    if (status !== 204) {
      yield put(actions.logOffFailed());
    } else {
      yield put(actions.logOffSucceeded());
    }
  } catch (e) {
    yield put(actions.logOffFailed());
  }
}
/* eslint-enable consistent-return */

function handleLogOffSucceeded() {
  accessTokenService.removeTokens();
}

function* authenticationSaga() {
  yield all([
    takeLatest(actions.LOG_IN, handleLogIn),
    takeLatest(actions.LOG_IN_SUCCEEDED, handleLogInSucceeded),
    takeLatest(actions.CHECK_LOGIN_STATUS, handleCheckLoginStatus),
    takeLatest(actions.LOG_OFF, handleLogOff),
    takeLatest(actions.LOG_OFF_SUCCEEDED, handleLogOffSucceeded),
  ]);
}

export default authenticationSaga;
