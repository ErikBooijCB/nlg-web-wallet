import React from 'react';

import AppBar from 'material-ui/AppBar';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import styled from 'styled-components';

import LoggedInMenu from './Menu/LoggedIn';

const Header = ( { loggedIn, subTitle } ) => (
  <AppBar
    iconElementRight={
      loggedIn
        ? <LoggedInMenu />
        : null
    }
    showMenuIconButton={ false }
    style={ appBarStyle }
    title={ <Title>Gulden Wallet{ subTitle && <SubTitle> | { subTitle }</SubTitle> }</Title> }
  />
);

const appBarStyle = {
  background: 'linear-gradient(to right, #1169D6, #2AB0FD)',
  position: 'fixed'
};

const Title = styled.span`
  font-weight: 300;
`;

const SubTitle = styled.span`
  font-weight: 500;
`;

Header.propTypes = {
  loggedIn: PropTypes.bool.isRequired,
  subTitle: PropTypes.node,
};

const mapStateToProps = state => {
  return {
    loggedIn: state.global.loggedIn,
  };
};

export default connect(mapStateToProps)(Header);
