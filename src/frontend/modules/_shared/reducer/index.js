import * as loginActions from '../../login/actions';

const defaultState = {
  bootCompleted: false,
  loggedIn:      false,
};

export default (state = defaultState, action) => {
  const newState = { ...state };

  switch (action.type) {
    case loginActions.LOG_IN_SUCCEEDED:
      newState.loggedIn = true;
      break;
    case loginActions.LOG_IN_FAILED:
      newState.loggedIn = false;
      break;
    case loginActions.LOGIN_STATUS_LOGGED_IN:
      newState.loggedIn = true;
      newState.bootCompleted = true;
      break;
    case loginActions.LOGIN_STATUS_NOT_LOGGED_IN:
      newState.loggedIn = false;
      newState.bootCompleted = true;
      break;
    default:
  }

  return newState;
};
