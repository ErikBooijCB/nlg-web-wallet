import React  from 'react';

import PropTypes from 'prop-types';
import styled from 'styled-components';

import theme  from '../../../theme';

const PaperTitle = ({ children }) => (
  <PaperTitleElement>
    { children }
  </PaperTitleElement>
);

const PaperTitleElement = styled.h1`
  align-items: center;
  background: linear-gradient(to right, ${theme.palette.primary1Color}, ${theme.palette.primary2Color});
  color: #fff;
  display: flex;
  font-size: 18px;
  font-weight: 400;
  justify-content: center;
  padding: 20px;
`;

PaperTitle.propTypes = {
  children: PropTypes.oneOfType([
    PropTypes.node,
    PropTypes.arrayOf(PropTypes.node),
  ]).isRequired,
};

export default PaperTitle;
