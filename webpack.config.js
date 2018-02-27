const GoogleFontsWebpackPlugin = require('google-fonts-webpack-plugin');

const fonts = require('./src/frontend/modules/_shared/fonts');

module.exports = {
  entry: './src/frontend/index.js',
  output: {
    filename: 'public/assets/application.js'
  },
  module: {
    loaders: [
      { test: /\.js$/, loader: 'babel-loader', exclude: /node_modules/ },
      { test: /\.jsx$/, loader: 'babel-loader', exclude: /node_modules/ }
    ]
  },
  "plugins": [
    new GoogleFontsWebpackPlugin({
      fonts,
      local: false
    })
  ]
};
