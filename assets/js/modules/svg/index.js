import svgxhr from 'webpack-svgstore-plugin/src/helpers/svgxhr';

// eslint-disable-next-line no-underscore-dangle
const __svg__ = {
  path: '../../../svg/**/*.svg',
  name: 'static/svg/[hash].sprite.svg',
};
svgxhr(__svg__);
