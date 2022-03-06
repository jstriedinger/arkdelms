const path = require('path');
const mergeme = require('webpack-merge');
const common = require('./webpack.common.js');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');



module.exports = mergeme(common, {
  module: {
    rules: [
      {
        test: /\.(png|jpe?g|svg)$/i,
        include: [
              path.resolve(__dirname, "src/img/opt")
          ],
        use: [
          {loader: 'file-loader', options:{name: '[name].[ext]',outputPath: 'img', } }
        ]
      },
    ]
  },
  watch: true,
  plugins: [
    new BrowserSyncPlugin({
        files: [
          './**/*.php',
          './**/*.twig'
        ],
        host: 'localhost',
        port: 3000,
        proxy: 'http://localhost/arkdelms',
        logPrefix: 'webpack',
        logLevel: 'debug',
        ghostMode: false
    })
  ]
});