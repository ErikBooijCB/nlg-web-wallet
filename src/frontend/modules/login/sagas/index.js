import { all, call, put, takeEvery } from 'redux-saga/effects';

import * as actions from '../actions';
import AccessTokenService from '../../_shared/services/AccessTokenService';
import { doPost } from '../../_shared/utilities/requestHelper';

const accessTokenService = new AccessTokenService();

function* handleLogIn( action ) {
  const { email, password, stayLoggedIn } = action.payload;

  try {
    const { status, body } = yield call(doPost, '/api/access-tokens', {
      email,
      password,
    });

    if ( status !== 201 ) {
      yield put(actions.logInFailed());
    } else {
      const { data: { accessToken, refreshToken } } = body;

      yield put(actions.logInSucceeded(accessToken, refreshToken, stayLoggedIn));
    }
  } catch ( e ) {
    yield put(actions.logInFailed());
  }
}

function* handleLogInSucceeded( action ) {
  accessTokenService.setToken(action.payload.accessToken, action.payload.refreshToken);
}

function* crypto() {
  yield all([
    takeEvery(actions.LOG_IN, handleLogIn),
    takeEvery(actions.LOG_IN_SUCCEEDED, handleLogInSucceeded),
  ]);
}

export default crypto;
