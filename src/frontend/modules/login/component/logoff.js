import React from 'react';

import Paper                  from 'material-ui/Paper';
import { bindActionCreators } from 'redux';
import { connect }            from 'react-redux';
import { Link }               from 'react-router-dom';
import styled                 from 'styled-components';
import PropTypes              from 'prop-types';

import Button       from './Button';
import Buttons      from './Buttons';
import PaperTitle   from './PaperTitle';
import * as actions from '../actions';
import FullScreen   from '../../_shared/components/FullScreen';
import Loader       from '../../_shared/components/Loader';

const LogOff = ({ loggingOff, logOff }) => (
  <FullScreen>
    <Paper style={ paperStyle }>
      <PaperTitle>LOG OFF</PaperTitle>
      <FormContent>Are you sure you want to log off?</FormContent>
      <Loader
        active={ loggingOff }
      />
      <Buttons>
        <Button
          primary
          label="Log Off"
          onClick={ logOff }
          tabIndex={ 0 }
          type="submit"
        />
        <Button
          secondary
          containerElement={ <Link href="/" to="/" /> }
          label="Go to homepage"
          tabIndex={ 0 }
        />
      </Buttons>
    </Paper>
  </FullScreen>
);

const FormContent = styled.div`
  min-height: 100px;
  padding: 20px;
`;

const paperStyle = {
  boxSizing: 'content-box',
  flex:      '0 1 400px',
};

const mapStateToProps = state => ({
  loggingOff: state.login.loggingOff,
});

const mapDispatchToProps = dispatch => (
  bindActionCreators({
    logOff: actions.logOff,
  }, dispatch)
);

LogOff.propTypes = {
  loggingOff: PropTypes.bool.isRequired,
  logOff:     PropTypes.func.isRequired,
};

export default connect(mapStateToProps, mapDispatchToProps)(LogOff);
