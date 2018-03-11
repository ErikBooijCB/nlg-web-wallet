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
    'max-len':                      [
      'error',
      { code: 120 },
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
    'object-curly-newline':         [
      'error',
      {
        ObjectExpression:  {
          consistent:    true,
          multiline:     true,
          minProperties: 8,
        },
        ObjectPattern:     {
          consistent:    true,
          multiline:     true,
          minProperties: 8,
        },
        ImportDeclaration: {
          consistent:    true,
          multiline:     true,
          minProperties: 8,
        },
        ExportDeclaration: {
          consistent:    true,
          multiline:     true,
          minProperties: 8,
        },
      },
    ],
    'object-curly-spacing':         [
      'error',
      'always',
    ],
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
