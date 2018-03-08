import React from 'react';

import styled from 'styled-components';

export default ({ children }) => (
  <FullScreen>{ children }</FullScreen>
);

const FullScreen = styled.div`
  height: 100%;
  width: 100%;
`;
