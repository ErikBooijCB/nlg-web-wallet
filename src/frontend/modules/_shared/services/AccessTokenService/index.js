import { doGet, doPost } from '../../utilities/requestHelper';

const accessTokenStorageKey = 'AccessToken';
const refreshTokenStorageKey = 'RefreshToken';

export default class {
  getAccessToken() {
    return window.localStorage.getItem(accessTokenStorageKey);
  }

  getRefreshToken() {
    return window.localStorage.getItem(refreshTokenStorageKey);
  }

  async refreshToken() {
    const { status, data } = await doPost('/api/access-tokens/' + this.getAccessToken(), {
      refresh: this.getRefreshToken()
    });

    if (status !== 'ok') {
      return false;
    }

    const { accessToken, refreshToken } = data;

    this.setToken(accessToken, refreshToken);

    return accessToken;
  }

  setToken(accessToken, refreshToken) {
    window.localStorage.setItem(accessTokenStorageKey, accessToken);
    window.localStorage.setItem(refreshTokenStorageKey, refreshToken);
  }

  async validateToken() {
    try {
      const { status } = await doGet('/api/access-tokens/' + this.getAccessToken());

      return status === 200;
    } catch (e) {
      return false;
    }
  }
};
