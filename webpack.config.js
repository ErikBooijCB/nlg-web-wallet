module.exports = {
  entry:  './src/frontend/index.js',
  output: {
    filename: 'public/assets/application.js',
  },
  module: {
    loaders: [
      { test: /\.js$/, loader: 'babel-loader', exclude: /node_modules/ },
      { test: /\.jsx$/, loader: 'babel-loader', exclude: /node_modules/ },
    ],
  },
};
