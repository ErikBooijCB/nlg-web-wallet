import React from 'react';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import styled from 'styled-components';

import FlatButton from 'material-ui/FlatButton';
import LinearProgress from 'material-ui/LinearProgress';
import Paper from 'material-ui/Paper';
import TextField from 'material-ui/TextField';
import Toggle from 'material-ui/Toggle';

import * as actions from '../actions';
import theme from '../../../theme';

let emailField, passwordField, stayLoggedInToggle;

const Login = ( { loggingIn, logIn } ) => (
  <div style={ { height: '100%' } }>
    <div style={ containerStyle }>
      <Paper style={ paperStyle }>
        <PaperTitle>LOG IN</PaperTitle>
        <FormContent>
          <TextField
            floatingLabelText="E-mail"
            ref={ ( input ) => emailField = input }
            style={ fieldStyle }
            type="email"
          />
          <TextField
            floatingLabelText="Password"
            ref={ ( input ) => passwordField = input }
            style={ fieldStyle }
            type="password"
          />
          <Toggle
            defaultToggled={ true }
            label="Keep me logged in"
            labelPosition="right"
            ref={ ( input ) => stayLoggedInToggle = input }
            style={ { marginTop: '20px' } }
          />
        </FormContent>
        <LinearProgress
          color="#f90"
          mode="indeterminate"
          style={ { background: '#fff', marginTop: '20px', opacity: loggingIn ? 1 : 0 } }
        />
        <div style={ buttonsStyle }>
          <FlatButton
            primary
            label="Log In"
            style={ buttonStyle }
            onClick={ ( e ) => {
              logIn(emailField.getValue(), passwordField.getValue(), stayLoggedInToggle.isToggled());
            } }
          />
          <FlatButton secondary label="Password Forgotten" style={ buttonStyle }/>
        </div>
      </Paper>
    </div>
  </div>
);

const FormContent = styled.div`
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

const buttonStyle = {
  float: 'right',
};

const buttonsStyle = {
  background: '#f8f8f8',
  overflow: 'hidden',
  padding: '10px',
};

const containerStyle = {
  alignItems: 'center',
  display: 'flex',
  height: '100%',
  justifyContent: 'center',
  width: '100%',
};

const fieldStyle = {
  width: '100%',
};

const paperStyle = {
  boxSizing: 'content-box',
  flex: '0 1 400px',
};

const mapStateToProps = state => {
  return {
    loggingIn: state.login.loggingIn,
  };
};

const mapDispatchToProps = dispatch => {
  return bindActionCreators({
    logIn: actions.logIn,
  }, dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(Login);
