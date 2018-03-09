import React from 'react';

import { Link } from 'react-router-dom';

import Divider    from 'material-ui/Divider';
import IconButton from 'material-ui/IconButton';
import IconMenu   from 'material-ui/IconMenu';
import MenuItem   from 'material-ui/MenuItem';

import LogOffIcon   from 'material-ui/svg-icons/action/power-settings-new';
import MoreVertIcon from 'material-ui/svg-icons/navigation/more-vert';
import SettingsIcon from 'material-ui/svg-icons/action/settings';

const LoggedIn = () => (
  <IconMenu
    anchorOrigin={ { horizontal: 'left', vertical: 'top' } }
    animated={ false }
    iconButtonElement={ <IconButton><MoreVertIcon /></IconButton> }
    iconStyle={ { fill: '#fff' } }
    targetOrigin={ { horizontal: 'left', vertical: 'bottom' } }
  >
    <MenuItem
      containerElement={ <Link href="/settings" to="/settings" /> }
      leftIcon={ <SettingsIcon /> }
    >
      Settings
    </MenuItem>
    <Divider />
    <MenuItem
      containerElement={ <Link href="/logoff" to="/logoff" /> }
      leftIcon={ <LogOffIcon /> }
    >
      Log Off
    </MenuItem>
  </IconMenu>
);

export default LoggedIn;
