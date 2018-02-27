export const LOG_IN = 'LOG_IN';
export const LOG_IN_SUCCEEDED = 'LOG_IN_SUCCEEDED';
export const LOG_IN_FAILED = 'LOG_IN_FAILED';

export const logIn = ( email, password, stayLoggedIn ) => {
  return {
    type: LOG_IN,
    payload: {
      email,
      password,
      stayLoggedIn,
    },
  };
};

export const logInSucceeded = ( accessToken, refreshToken, stayLoggedIn ) => {
  return {
    type: LOG_IN_SUCCEEDED,
    payload: {
      accessToken,
      refreshToken,
      stayLoggedIn,
    },
  };
};

export const logInFailed = () => {
  return {
    type: LOG_IN_FAILED,
    payload: {
      message: 'You could not be logged in',
    },
  };
};
