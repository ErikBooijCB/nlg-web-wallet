import * as actions from '../actions/statusBar';

const defaultState = {
  blocks:      -1,
  connections: -1,
  isFetching:  false,
};

export default (state = defaultState, action) => {
  const newState = { ...state };

  switch (action.type) {
    case actions.FETCH_STATUS_BAR_DATA:
      newState.isFetching = true;
      break;
    case actions.FETCH_STATUS_BAR_DATA_SUCCEEDED:
      newState.isFetching = false;
      newState.blocks = action.payload.blocks;
      newState.connections = action.payload.connections;
      break;
    case actions.FETCH_STATUS_BAR_DATA_FAILED:
      newState.isFetching = false;
      break;
    default:
  }

  return newState;
};
