import { doGet, doPost } from '../../utilities/requestHelper';

export default class {
  getAccessToken() {
    return window.localStorage.getItem('accessToken');
  }

  getRefreshToken() {
    return window.localStorage.getItem('refreshToken');
  }

  async refreshToken() {
    const { data: { accessToken, refreshToken } } = await doPost('/api/access-tokens/' + this.getAccessToken(), {
      refresh: this.getRefreshToken()
    });

    this.setToken(accessToken, refreshToken);

    return accessToken;
  }

  setToken(accessToken, refreshToken) {
    window.localStorage.setItem('accessToken', accessToken);
    window.localStorage.setItem('refreshToken', refreshToken);
  }

  async validateToken() {
    try {
      const res = await doGet('/api/access-tokens/' + this.getAccessToken());

      return true;
    } catch (e) {
      return false;
    }
  }
};
