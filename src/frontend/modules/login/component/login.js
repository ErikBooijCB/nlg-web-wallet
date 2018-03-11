import React from 'react';

import PropTypes              from 'prop-types';
import { connect }            from 'react-redux';
import { bindActionCreators } from 'redux';
import { Field, reduxForm }   from 'redux-form';
import { TextField }          from 'redux-form-material-ui';
import styled                 from 'styled-components';

import FlatButton     from 'material-ui/FlatButton';
import Paper          from 'material-ui/Paper';
import SnackBar       from 'material-ui/Snackbar';

import * as actions from '../actions';
import FullScreen   from '../../_shared/components/FullScreen';
import Loader       from '../../_shared/components/Loader';
import theme        from '../../../theme';

const reduxFormSettings = {
  form:          'logIn',
  initialValues: {
    email:    '',
    password: '',
  },
};

const Login = ({
  handleSubmit,
  loggingIn,
  logIn,
  loginFailed,
}) => (
  <FullScreen>
    <SnackBar
      autoHideDuration={ 3000 }
      bodyStyle={ { background: '#f88' } }
      contentStyle={ { color: '#fff' } }
      message="You could not be logged in. Please try again."
      open={ loginFailed }
    />
    <Paper style={ paperStyle }>
      <form onSubmit={ handleSubmit(logIn) }>
        <PaperTitle>LOG IN</PaperTitle>
        <FormContent>
          <Field
            autoComplete="username email"
            autoFocus
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
        <Loader
          active={ loggingIn }
        />
        <Buttons>
          <Button
            primary
            label="Log In"
            tabIndex={ 0 }
            type="submit"
          />
          <Button
            secondary
            label="Password Forgotten"
            tabIndex={ 0 }
          />
        </Buttons>
      </form>
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

const fieldStyle = {
  width: '100%',
};

const paperStyle = {
  boxSizing: 'content-box',
  flex:      '0 1 400px',
};

const mapStateToProps = state => ({
  loggingIn:   state.login.loggingIn,
  loginFailed: state.login.loginFailed,
});

const mapDispatchToProps = dispatch => (
  bindActionCreators({
    logIn: actions.logIn,
  }, dispatch)
);

Login.propTypes = {
  handleSubmit: PropTypes.func.isRequired,
  logIn:        PropTypes.func.isRequired,
  loggingIn:    PropTypes.bool.isRequired,
  loginFailed:  PropTypes.bool.isRequired,
};

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm(reduxFormSettings)(Login));
