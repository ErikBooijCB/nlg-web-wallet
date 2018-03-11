import { createAction } from 'redux-actions';

export const FETCH_STATUS_BAR_DATA = 'FETCH_STATUS_BAR_DATA';
export const FETCH_STATUS_BAR_DATA_SUCCEEDED = 'FETCH_STATUS_BAR_DATA_SUCCEEDED';
export const FETCH_STATUS_BAR_DATA_FAILED = 'FETCH_STATUS_BAR_DATA_FAILED';

export const fetchStatusBarData = createAction(FETCH_STATUS_BAR_DATA);
export const fetchStatusBarDataSucceeded = createAction(FETCH_STATUS_BAR_DATA_SUCCEEDED);
export const fetchStatusBarDataFailed = createAction(FETCH_STATUS_BAR_DATA_FAILED);
