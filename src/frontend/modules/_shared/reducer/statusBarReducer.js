import * as actions from '../actions/statusBar';

const defaultState = {
  blocks:      0,
  connections: 0,
  healthy:     false,
  isFetching:  false,
};

export default (state = defaultState, action) => {
  const newState = { ...state };

  switch (action.type) {
    case actions.FETCH_STATUS_BAR_DATA:
      newState.isFetching = true;
      break;
    case actions.FETCH_STATUS_BAR_DATA_SUCCEEDED: {
      newState.isFetching = false;

      const { blocks, connections, healthy } = action.payload;

      return setStatusBarDetails(
        newState,
        blocks,
        connections,
        healthy,
      );
    }
    case actions.FETCH_STATUS_BAR_DATA_FAILED:
      newState.isFetching = false;

      return setStatusBarDetails(newState);
    default:
  }

  return newState;
};

function setStatusBarDetails(state, blocks = 0, connections = 0, healthy = false) {
  const newState = { ...state };

  newState.blocks = blocks;
  newState.connections = connections;
  newState.healthy = healthy;

  return newState;
}
