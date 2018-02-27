import * as actions from '../actions';

const defaultState = {
  loggingIn: false,
  loginFailed: false
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
  }

  return newState;
};
