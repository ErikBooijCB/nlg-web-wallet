import React from 'react';

import FlatButton             from 'material-ui/FlatButton';
import Paper                  from 'material-ui/Paper';
import { bindActionCreators } from 'redux';
import { connect }            from 'react-redux';
import { Link }               from 'react-router-dom';
import styled                 from 'styled-components';
import PropTypes              from 'prop-types';

import * as actions from '../actions';
import FullScreen   from '../../_shared/components/FullScreen';
import Loader       from '../../_shared/components/Loader';
import theme        from '../../../theme';

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

const Button = styled(FlatButton)`
  float: right;
`;

const Buttons = styled.div`
  background: #f8f8f8;
  overflow:   hidden;
  padding:    10px;
`;

const FormContent = styled.div`
  min-height: 100px;
  padding: 20px;
`;

const PaperTitle = styled.h1`
  align-items: center;
  background: linear-gradient(to right, ${theme.palette.primary1Color}, ${theme.palette.primary2Color});
  color: #fff;
  display: flex;
  font-size: 18px;
  font-weight: 400;
  justify-content: center;
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
