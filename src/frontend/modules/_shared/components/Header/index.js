import React from 'react';

import AppBar      from 'material-ui/AppBar';
import PropTypes   from 'prop-types';
import { connect } from 'react-redux';
import styled      from 'styled-components';

import LoggedInMenu from './Menu/LoggedIn';
import theme from '../../../../theme';

const Header = ({ loggedIn, subTitle = false }) => (
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
  background: `linear-gradient(to right, ${theme.palette.primary1Color}, ${theme.palette.primary2Color})`,
  left:       0,
  position:   'fixed',
  top:        0,
};

const Title = styled.span`
  font-weight: 500;
`;

const SubTitle = styled.span`
  font-weight: 300;
`;

Header.defaultProps = {
  subTitle: false,
};

Header.propTypes = {
  loggedIn: PropTypes.bool.isRequired,
  subTitle: PropTypes.node,
};

const mapStateToProps = state => ({
  loggedIn: state.global.loggedIn,
});

export default connect(mapStateToProps)(Header);
