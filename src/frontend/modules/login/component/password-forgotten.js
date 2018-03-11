import React from 'react';

import Paper    from 'material-ui/Paper';
import { Link } from 'react-router-dom';
import styled   from 'styled-components';

import Button     from './Button';
import Buttons    from './Buttons';
import PaperTitle from './PaperTitle';
import FullScreen from '../../_shared/components/FullScreen';

const PasswordForgotten = () => (
  <FullScreen>
    <Container>
      <PaperTitle>PASSWORD FORGOTTEN</PaperTitle>
      <Content>
        In order to reset your password, you&apos;ll need access to the host system your installation is running on.
        In the installation directory of the application run:
        <Code>
          $ ./wallet reset-password
        </Code>
        It will then guide you through the process of setting a new password.
      </Content>
      <Buttons>
        <Button
          secondary
          containerElement={ <Link href="/login" to="/login" /> }
          label="Back to login"
          tabIndex={ 0 }
        />
      </Buttons>
    </Container>
  </FullScreen>
);

const Code = styled.div`
  background: #f4f4f4;
  color: #444;
  font-family: 'Roboto Mono';
  margin: 20px 0;
  padding: 20px;
`;

const Container = styled(Paper)`
  flex: 600px 0 1;
  margin: 20px;
`;

const Content = styled.div`
  line-height: 2em;
  padding: 20px;
`;

export default PasswordForgotten;
