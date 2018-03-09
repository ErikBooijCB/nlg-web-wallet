import React from 'react';

import PropTypes              from 'prop-types';
import { connect }            from 'react-redux';
import { bindActionCreators } from 'redux';

import { checkLoginStatus } from '../../../login/actions';
import FullScreen           from '../../components/FullScreen';
import Login                from '../../../login/component';
import PreBoot              from '../PreBoot';
import Router               from '../../../../router';

const renderBootCompleted = loggedIn => (
  <div>
    {
      loggedIn
        ? <Router />
        : <Login />
    }
  </div>
);

const AppRoot = ({ boot, bootCompleted, loggedIn }) => (
  <FullScreen>
    { bootCompleted
      ? renderBootCompleted(loggedIn)
      : <PreBoot boot={ boot } /> }
  </FullScreen>
);

const mapStateToProps = state => ({
  bootCompleted: state.global.bootCompleted,
  loggedIn:      state.global.loggedIn,
});

const mapDispatchToProps = dispatch => (
  bindActionCreators({
    boot: checkLoginStatus,
  }, dispatch)
);

AppRoot.propTypes = {
  boot:          PropTypes.func.isRequired,
  bootCompleted: PropTypes.bool.isRequired,
  loggedIn:      PropTypes.bool.isRequired,
};

export default connect(mapStateToProps, mapDispatchToProps)(AppRoot);
