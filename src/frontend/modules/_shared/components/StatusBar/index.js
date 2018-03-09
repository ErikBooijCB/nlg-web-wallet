import React from 'react';

import styled from 'styled-components';

const StatusBar = () => (
  <Container />
);

const Container = styled.div`
  align-items: center;
  background: #fff;
  bottom: 0;
  box-shadow: 0 -2px 2px rgba(0, 0, 0, .1);
  display: flex;
  height: 24px;
  justify-content: center;
  left: 0;
  position: fixed;
  right: 0;
`;

export default StatusBar;
