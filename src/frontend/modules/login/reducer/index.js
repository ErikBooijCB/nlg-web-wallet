import * as actions from '../actions';

const defaultState = {
  loggingIn:   false,
  loggingOff:  false,
  loginFailed: false,
};

export default (state = defaultState, action) => {
  const newState = { ...state };

  switch (action.type) {
    case actions.LOG_IN:
      newState.loggingIn = true;
      newState.loginFailed = false;
      break;
    case actions.LOG_IN_SUCCEEDED:
      newState.loggingIn = false;
      newState.loginFailed = false;
      break;
    case actions.LOG_IN_FAILED:
      newState.loggingIn = false;
      newState.loginFailed = true;
      break;
    case actions.LOG_OFF:
      newState.loggingOff = true;
      break;
    case actions.LOG_OFF_SUCCEEDED:
      newState.loggingOff = false;
      break;
    case actions.LOG_OFF_FAILED:
      newState.loggingOff = false;
      break;
    default:
  }

  return newState;
};
