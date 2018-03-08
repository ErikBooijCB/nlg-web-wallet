import React from 'react';

import CircularProgress from 'material-ui/CircularProgress';
import PropTypes from 'prop-types';
import styled from 'styled-components';


class PreBoot extends React.Component {
  componentDidMount() {
    this.props.boot();
  }

  render() {
    return (
      <CenteredFullPage>
        <CircularProgress size={ 60 } thickness={ 7 }/>
      </CenteredFullPage>
    );
  }
}

const CenteredFullPage = styled.div`
  align-items: center;
  display: flex;
  height: 100%;
  justify-content: center;
  width: 100%;
`;

PreBoot.propTypes = {
  boot: PropTypes.func.isRequired
};

export default PreBoot;
