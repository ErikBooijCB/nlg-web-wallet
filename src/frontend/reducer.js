import { combineReducers } from 'redux';

import global from './modules/_shared/reducer';
import login from './modules/login/reducer';

export default combineReducers({
  global,
  login
});
