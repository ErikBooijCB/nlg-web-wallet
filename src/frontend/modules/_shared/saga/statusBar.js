import { call, put, takeLatest } from 'redux-saga/effects';

import * as actions from '../actions/statusBar';
import { doGet } from '../utilities/requestHelper';

/* eslint-disable consistent-return */
function* handleFetchStatusBarData() {
  try {
    const { status, body } = yield call(doGet, '/api/node');

    if (status !== 200) {
      yield put(actions.fetchStatusBarDataFailed());
    } else {
      yield put(actions.fetchStatusBarDataSucceeded(body.data));
    }
  } catch (e) {
    yield put(actions.fetchStatusBarDataFailed());
  }
}
/* eslint-enable consistent-return */

function* authenticationSaga() {
  yield takeLatest(actions.FETCH_STATUS_BAR_DATA, handleFetchStatusBarData);
}

export default authenticationSaga;
