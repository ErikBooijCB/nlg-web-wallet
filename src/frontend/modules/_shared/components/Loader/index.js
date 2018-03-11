import React from 'react';

import LinearProgress from 'material-ui/LinearProgress';
import PropTypes      from 'prop-types';

const Loader = ({ active }) => (
  <LinearProgress
    color="#f90"
    mode="indeterminate"
    style={ { background: '#fff', marginTop: '20px', opacity: active ? 1 : 0 } }
  />
);

Loader.propTypes = {
  active: PropTypes.bool.isRequired,
};

export default Loader;
