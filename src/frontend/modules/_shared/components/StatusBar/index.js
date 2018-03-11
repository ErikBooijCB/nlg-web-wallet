import React from 'react';

import { connect } from 'react-redux';
import PropTypes   from 'prop-types';
import styled      from 'styled-components';

import theme from '../../../../theme';

const StatusBar = ({ blocks, connections }) => (
  <Container>
    <ul>
      <StatusBarItem><Label>Block</Label> { blocks }</StatusBarItem>
      <StatusBarItem><Label>Connections</Label> { connections }</StatusBarItem>
    </ul>
  </Container>
);

const Container = styled.div`
  align-items: center;
  background: #fff;
  bottom: 0;
  box-shadow: 0 -2px 2px rgba(0, 0, 0, .1);
  display: flex;
  height: 30px;
  justify-content: center;
  left: 0;
  position: fixed;
  right: 0;
`;

const StatusBarItem = styled.li`
  color: ${theme.palette.primary2Color};
  display: inline;
  font-size: 14px;
  font-weight: 500;
  list-style: none;
  margin: 0 10px;
  text-transform: uppercase;
`;

const Label = styled.span`
  color: #777;
`;

StatusBar.propTypes = {
  blocks:      PropTypes.number.isRequired,
  connections: PropTypes.number.isRequired,
};

const mapStateToProps = state => ({
  blocks:      state.statusBar.blocks,
  connections: state.statusBar.connections,
});

export default connect(mapStateToProps)(StatusBar);
