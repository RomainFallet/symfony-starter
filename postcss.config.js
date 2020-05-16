const config = {
  plugins: [require('postcss-preset-env')]
}

if (process.env.NODE_ENV === 'production') {
  config.plugins = [
    ...config.plugins,
    require('@fullhuman/postcss-purgecss')({
      content: ['./templates/**/*.html.twig']
    })
  ]
}
