const preset = require('jest-preset-miaoxing/jest-preset');

module.exports = {
  ...preset,
  moduleNameMapper: {
    ...preset.moduleNameMapper,
    '@miaoxing/product/(.*)': '<rootDir>/$1',
    '@miaoxing/product': '<rootDir>',
  },
};
