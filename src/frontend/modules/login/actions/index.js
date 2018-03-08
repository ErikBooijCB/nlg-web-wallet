import { createAction } from 'redux-actions';

export const LOG_IN = 'LOG_IN';
export const LOG_IN_SUCCEEDED = 'LOG_IN_SUCCEEDED';
export const LOG_IN_FAILED = 'LOG_IN_FAILED';

export const CHECK_LOGIN_STATUS = 'CHECK_LOGIN_STATUS';
export const LOGIN_STATUS_NOT_LOGGED_IN = 'LOGIN_STATUS_NOT_LOGGED_IN';
export const LOGIN_STATUS_LOGGED_IN = 'LOGIN_STATUS_LOGGED_IN';

export const logIn = createAction(LOG_IN);
export const logInSucceeded = createAction(LOG_IN_SUCCEEDED);
export const logInFailed = createAction(LOG_IN_FAILED);

export const checkLoginStatus = createAction(CHECK_LOGIN_STATUS);
export const loginStatusNotLoggedIn = createAction(LOGIN_STATUS_NOT_LOGGED_IN);
export const loginStatusLoggedIn = createAction(LOGIN_STATUS_LOGGED_IN);
