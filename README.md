# Gulden Web Wallet
This application provides a simple to use interface to a Gulden node, available in your browser. If you are, for instance, running a Gulden node on a Raspberry Pi in order to take part in [witnessing](https://gulden.com/pow2), this application will allow you to maintain your wallet with ease, while providing insights into your witnessing proceeds.

## ✅ Code Quality
[![Build Status](https://travis-ci.org/ErikBooij/nlg-web-wallet.svg?branch=master)](https://travis-ci.org/ErikBooij/nlg-web-wallet) [![Maintainability](https://api.codeclimate.com/v1/badges/92dc6479741859b55c28/maintainability)](https://codeclimate.com/github/ErikBooij/nlg-web-wallet/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/92dc6479741859b55c28/test_coverage)](https://codeclimate.com/github/ErikBooij/nlg-web-wallet/test_coverage)

## 👨‍👩‍👧‍👦 Contributing
Pull Requests to this repository are more than welcome. Below you will find instructions on how to set up a development environment.

To set up a local development environment, you'll need to have the following software installed:

- [Docker](https://www.docker.com/community-edition)
- [PHP 7.0 or above](http://php.net/manual/en/install.php)
- [NodeJS & NPM](https://docs.npmjs.com/getting-started/installing-node)

Once all the prerequisites are in place and you have forked and cloned the repository, run the following command to prepare all the dependencies. It will build and start all the required Docker containers and install the PHP and NodeJS dependencies.

```bash
$ ./install-development
```

As soon as this step is finished, you should be able to view the interface on [localhost](http://localhost), or any other domain you point to it.

### Gulden Node
For most of the functionality, the system will need a Gulden node to talk to. You can easily set one up, following the [steps described on the Gulden website](https://dev.gulden.com/contribute#nodes). Keep in mind that you'll want to set `disablewallet` to `0` for the wallet to function.

For the purpose of development, you'll probably want to run it on a testnet, so you can test/build payment functionality without actually spending any Guldens. To do so, alter your `Gulden.conf` to contain the following lines:

```
disablewallet=0
maxconnections=20
rpcuser=[... choose your own username]
rpcpassword=[... choose your own password]

testnet=C1511943855:60
addnode=178.62.195.19
addnode=45.32.253.142
addnode=199.247.1.84
addnode=104.238.189.72
addnode=149.210.165.218
```

Depending on your configuration, you might also need to add another line to actually accept incoming RPC connections to the node (alter this to meet your network configuration):

```
rpcallowip=192.168.0.1/24
```

### Credentials
By default the application will use the credentials in `etc/secrets.php.dist`, but you'll probably need to alter them to support your own Gulden node. **Do not** alter the `.dist` file, because you might accidentally check your credentials into version control. Instead copy the file to `etc/secrets.php` and put your credentials in there. The file will automatically be picked up by the application, but is in .gitignore to prevent checking it in.

The default username is `john@doe.com` and the password is `testtest`.

### Testing
If you want to make sure your code passes continuous integration before submitting a Pull Request, you can run

```bash
$ composer test-ci
```

This will run linting on both PHP and Javascript files, it will run the unit tests, static analysis and acceptance tests. This is the exact check that also runs in [Travis CI](https://travis-ci.org/ErikBooij/nlg-web-wallet).

There are more composer commands to execute the separate steps. A few examples:

| Command                          | Function                                                                         |
| ---------------------------------|----------------------------------------------------------------------------------|
| `$ composer test-acceptance`     | Run acceptance tests with [CodeCeption](https://codeception.com/)                |
| `$ composer test-code-style:js`  | Run Javascript linting with [ESLint](https://eslint.org/)                        |
| `$ composer test-code-style:php` | Run PHP linting with [CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) |
| `$ composer test-coverage:html`  | Generate unit test coverage and write to `coverage/index.html`                   |
| `$ composer test-static`         | Perform static analysis with [Psalm](https://github.com/vimeo/psalm)             |
| `$ composer test-unit`           | Run unit tests with [PHPUnit](https://phpunit.de/)                               |


### Frontend
The frontend is built in [React](https://reactjs.org/) and relies heavily on [Material UI](https://material-ui.com). If you're working on frontend code, simply run the following command to watch your files and rebuild on changes. [Webpack](https://webpack.js.org/) is used for building the Javascript assets (without hot reloading).

```bash
$ npm start
```

## 👩‍💻 Installation instructions
_Instructions will follow_
