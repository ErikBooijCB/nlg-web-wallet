import React from 'react';

import PropTypes from 'prop-types';
import styled    from 'styled-components';

const FullScreen = ({ children }) => (
  <FullScreenDiv>{ children }</FullScreenDiv>
);

const FullScreenDiv = styled.div`
  height: 100%;
  width: 100%;
`;

FullScreen.propTypes = {
  children: PropTypes.oneOfType([
    PropTypes.arrayOf(PropTypes.node),
    PropTypes.node,
  ]).isRequired,
};

export default FullScreen;
