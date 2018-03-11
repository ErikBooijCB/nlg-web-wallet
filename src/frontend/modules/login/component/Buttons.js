import React from 'react';

import PropTypes from 'prop-types';
import styled    from 'styled-components';

const Buttons = ({ children }) => (
  <ButtonsElement>
    { children }
  </ButtonsElement>
);

const ButtonsElement = styled.div`
  background: #f8f8f8;
  overflow:   hidden;
  padding:    10px;
`;

Buttons.propTypes = {
  children: PropTypes.oneOfType([
    PropTypes.node,
    PropTypes.arrayOf(PropTypes.node),
  ]).isRequired,
};

export default Buttons;
