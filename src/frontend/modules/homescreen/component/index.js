import React from 'react';

import FullScreen from '../../_shared/components/FullScreen';
import Header from '../../_shared/components/Header';
import StatusBar from '../../_shared/components/StatusBar';

const HomeScreen = () => (
  <FullScreen>
    <Header subTitle="Home"/>
    <StatusBar/>
  </FullScreen>
);

export default HomeScreen;
