import { combineReducers } from 'redux';
import { reducer as form } from 'redux-form';

import global    from './modules/_shared/reducer/globalReducer';
import statusBar from './modules/_shared/reducer/statusBarReducer';
import login     from './modules/login/reducer';

export default combineReducers({
  global,
  login,
  form,
  statusBar,
});
