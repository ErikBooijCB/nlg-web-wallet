import React from 'react';

import { connect } from 'react-redux';
import PropTypes   from 'prop-types';
import styled      from 'styled-components';

const StatusBar = ({ blocks, connections, healthy }) => (
  <Container>
    <ul>
      <HealthStatus healthy={ healthy }><Label>Status</Label> { healthy ? 'Healthy' : 'Not healthy' }</HealthStatus>
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
  color: #555;
  display: inline;
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0.1em;
  line-height: 30px;
  list-style: none;
  margin: 0 10px;
  text-transform: uppercase;
`;

const HealthStatus = StatusBarItem.extend`
  &::before {
    background: ${({ healthy }) => (healthy ? '#0b0' : '#b00')};
    border-radius: 50%;
    content: '';
    display: inline-block;
    height: 10px;
    margin-right: 10px;
    width: 10px;
  }
`;

const Label = styled.span`
  color: #999;
`;

StatusBar.propTypes = {
  blocks:      PropTypes.number.isRequired,
  connections: PropTypes.number.isRequired,
  healthy:     PropTypes.bool.isRequired,
};

const mapStateToProps = state => ({
  blocks:      state.statusBar.blocks,
  connections: state.statusBar.connections,
  healthy:     state.statusBar.healthy,
});

export default connect(mapStateToProps)(StatusBar);
