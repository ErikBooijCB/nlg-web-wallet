import React from 'react';

import PropTypes      from 'prop-types';
import { withRouter } from 'react-router-dom';
import styled         from 'styled-components';

const NotFound = ({ location }) => (
  <FullPage>
    <Message>
      There is no content available on
      <Path>{ location.pathname }</Path>
    </Message>
  </FullPage>
);

const FullPage = styled.div`
  align-items: center;
  background: #eee;
  display: flex;
  height: 100%;
  justify-content: center;
  width: 100%;
`;

const Message = styled.div`
  color: #555;
  font-family: Roboto Mono;
  font-weight: 400;
  padding: 0 40px;
  width: 100%;
`;

const Path = styled.div`
  font-weight: 500;
  overflow: hidden;
  font-family: Roboto Mono;
  text-overflow: ellipsis;
  white-space: nowrap;
`;

NotFound.propTypes = {
  location: PropTypes.shape({
    pathname: PropTypes.string.isRequired,
  }).isRequired,
};

export default withRouter(NotFound);
