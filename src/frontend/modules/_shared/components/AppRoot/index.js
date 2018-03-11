import React from 'react';

import PropTypes   from 'prop-types';
import { connect } from 'react-redux';

import PreBoot                from '../PreBoot';
import { fetchStatusBarData } from '../../actions/statusBar';
import FullScreen             from '../../components/FullScreen';
import { checkLoginStatus }   from '../../../login/actions';
import Login                  from '../../../login/component';
import Router                 from '../../../../router';

const AppRoot = ({ boot, bootCompleted, loggedIn }) => (
  <FullScreen>
    { bootCompleted
      ? renderBootCompleted(loggedIn)
      : <PreBoot boot={ boot } /> }
  </FullScreen>
);

const renderBootCompleted = loggedIn => (
  <FullScreen>
    {
      loggedIn
        ? <Router />
        : <Login />
    }
  </FullScreen>
);

const mapStateToProps = state => ({
  bootCompleted: state.global.bootCompleted,
  loggedIn:      state.global.loggedIn,
});

const mapDispatchToProps = dispatch => ({
  boot: () => {
    dispatch(checkLoginStatus());
    dispatch(fetchStatusBarData());
  },
});

AppRoot.propTypes = {
  boot:          PropTypes.func.isRequired,
  bootCompleted: PropTypes.bool.isRequired,
  loggedIn:      PropTypes.bool.isRequired,
};

export default connect(mapStateToProps, mapDispatchToProps)(AppRoot);
