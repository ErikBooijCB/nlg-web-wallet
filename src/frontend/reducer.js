import { combineReducers } from 'redux';
import { reducer as form } from 'redux-form';

import global from './modules/_shared/reducer';
import login  from './modules/login/reducer';

export default combineReducers({
  global,
  login,
  form,
});
