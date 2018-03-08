import { all, call, put, takeLatest } from 'redux-saga/effects';

import * as actions from '../actions';
import AccessTokenService from '../../_shared/services/AccessTokenService';
import { doPost } from '../../_shared/utilities/requestHelper';

const accessTokenService = new AccessTokenService();

function* handleCheckLoginStatus() {
  try {
    const token = accessTokenService.getAccessToken();

    if (!token) {
      yield put(actions.loginStatusNotLoggedIn());

      return 0;
    }

    const tokenValidity = yield call([accessTokenService, 'validateToken'], token);

    if (tokenValidity) {
      yield put(actions.loginStatusLoggedIn());
    } else {

      yield attemptRefreshOfToken();
    }
  } catch (e) {
    yield put(actions.loginStatusNotLoggedIn());
  }
}

function* attemptRefreshOfToken() {
  try {
    const newToken = yield call([accessTokenService, 'refreshToken']);

    if (newToken) {
      yield put(actions.loginStatusLoggedIn());
    } else {
      yield put(actions.loginStatusNotLoggedIn());
    }
  } catch (e) {
    yield put(actions.loginStatusNotLoggedIn());
  }
}

function* handleLogIn( action ) {
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

    if ( status !== 201 ) {
      yield put(actions.logInFailed());
    } else {
      const { data: { accessToken, refreshToken } } = body;

      yield put(actions.logInSucceeded({ accessToken, refreshToken }));
    }
  } catch ( e ) {
    yield put(actions.logInFailed());
  }
}

function* handleLogInSucceeded( action ) {
  const { accessToken, refreshToken } = action.payload;

  accessTokenService.setToken(accessToken, refreshToken);
}

function* authenticationSaga() {
  yield all([
    takeLatest(actions.LOG_IN, handleLogIn),
    takeLatest(actions.LOG_IN_SUCCEEDED, handleLogInSucceeded),
    takeLatest(actions.CHECK_LOGIN_STATUS, handleCheckLoginStatus),
  ]);
}

export default authenticationSaga;
