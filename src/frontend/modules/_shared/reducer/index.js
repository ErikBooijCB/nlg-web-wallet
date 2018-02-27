import * as loginActions from '../../login/actions';

const defaultState = {
  loggedIn: false
};

export default (state = defaultState, action) => {
  const newState = { ...state };

  switch (action.type) {
    case loginActions.LOG_IN_SUCCEEDED:
      newState.loggedIn = true;
      break;
  }

  return newState;
};
