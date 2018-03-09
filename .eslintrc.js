module.exports = {
  env:           {
    browser: true,
    es6:     true,
  },
  extends:       [
    'airbnb',
  ],
  parser:        'babel-eslint',
  parserOptions: {
    ecmaFeatures: {
      experimentalObjectRestSpread: true,
      jsx:                          true,
    },
    sourceType:   'module',
  },
  plugins:       [
    'react',
  ],
  rules:         {
    'array-bracket-spacing':        [ 'error', 'always' ],
    'arrow-parens':                 [ 'error', 'as-needed' ],
    'class-methods-use-this':       [ 'off' ],
    indent:                         [
      'error',
      2,
      { SwitchCase: 1 },
    ],
    'key-spacing':                  [
      'error',
      {
        multiLine: {
          beforeColon: false,
          afterColon:  true,
          mode:        'strict',
          align:       {
            beforeColon: false,
            afterColon:  true,
            on:          'value',
            mode:        'minimum',
          },
        },
      },
    ],
    'linebreak-style':              [
      'error',
      'unix',
    ],
    'no-multi-spaces':              [
      'error',
      {
        exceptions: {
          Property:           true,
          ImportDeclaration:  true,
          VariableDeclarator: true,
        },
      },
    ],
    'no-use-before-define':         [ 0 ],
    'object-curly-spacing':         [ 'error', 'always' ],
    quotes:                         [
      'error',
      'single',
    ],
    'react/jsx-filename-extension': [
      'error',
      {
        extensions: [ '.js', '.jsx' ],
      },
    ],
    'react/jsx-curly-spacing':      [
      'error',
      { when: 'always' },
    ],
    semi:                           [
      'error',
      'always',
    ],
  },
};
