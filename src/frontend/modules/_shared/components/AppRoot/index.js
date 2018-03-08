import React from 'react';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { checkLoginStatus } from '../../../login/actions';
import FullScreen from '../../components/FullScreen';
import Login from '../../../login/component';
import PreBoot from '../PreBoot';
import Router from '../../../../router';

const AppRoot = ( { boot, bootCompleted, loggedIn } ) => (
  <FullScreen>
    { bootCompleted
      ? loggedIn
        ? <Router/>
        : <Login/>
      : <PreBoot boot={ boot }/> }
  </FullScreen>
);

const mapStateToProps = state => {
  return {
    bootCompleted: state.global.bootCompleted,
    loggedIn: state.global.loggedIn
  };
};

const mapDispatchToProps = dispatch => {
  return bindActionCreators({
    boot: checkLoginStatus
  }, dispatch)
};

export default connect(mapStateToProps, mapDispatchToProps)(AppRoot);
