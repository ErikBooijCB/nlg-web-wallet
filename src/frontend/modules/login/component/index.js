import React from 'react';

import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Field, reduxForm } from 'redux-form';
import { TextField } from 'redux-form-material-ui';
import styled from 'styled-components';

import FlatButton from 'material-ui/FlatButton';
import LinearProgress from 'material-ui/LinearProgress';
import Paper from 'material-ui/Paper';
import SnackBar from 'material-ui/Snackbar';

import * as actions from '../actions';
import theme from '../../../theme';

const reduxFormSettings = {
  form: 'logIn',
  initialValues: {
    email: '',
    password: '',
  },
};

class Login extends React.Component {
  render() {
    return (
      <div style={ containerStyle }>
        <SnackBar
          autoHideDuration={ 3000 }
          bodyStyle={ { background: '#f88' } }
          contentStyle={ { color: '#fff' } }
          message="You could not be logged in. Please try again."
          open={ this.props.loginFailed }
        />
        <Paper style={ paperStyle }>
          <form onSubmit={ this.props.handleSubmit(this.props.logIn) }>
            <PaperTitle>LOG IN</PaperTitle>
            <FormContent>
              <Field
                autoComplete="username email"
                autoFocus={ true }
                component={ TextField }
                floatingLabelText="E-mail"
                name="email"
                style={ fieldStyle }
                tabIndex={ 0 }
                type="email"
              />
              <Field
                autoComplete="current-password"
                component={ TextField }
                floatingLabelText="Password"
                name="password"
                style={ fieldStyle }
                tabIndex={ 0 }
                type="password"
              />
            </FormContent>
            <LinearProgress
              color="#f90"
              mode="indeterminate"
              style={ { background: '#fff', marginTop: '20px', opacity: this.props.loggingIn ? 1 : 0 } }
            />
            <div style={ buttonsStyle }>
              <FlatButton
                primary
                label="Log In"
                style={ buttonStyle }
                tabIndex={ 0 }
                type="submit"
              />
              <FlatButton
                secondary
                label="Password Forgotten"
                style={ buttonStyle }
                tabIndex={ 0 }
              />
            </div>
          </form>
        </Paper>
      </div>
    );
  }
}

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
    loginFailed: state.login.loginFailed,
  };
};

const mapDispatchToProps = dispatch => {
  return bindActionCreators({
    logIn: actions.logIn,
  }, dispatch);
};

Login.propTypes = {
  logIn: PropTypes.func.isRequired,
  loggingIn: PropTypes.bool.isRequired,
  loginFailed: PropTypes.bool.isRequired,
};

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm(reduxFormSettings)(Login));
