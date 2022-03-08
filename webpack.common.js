const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyWebpackPlugin = require('copy-webpack-plugin');
const SpeedMeasurePlugin = require("speed-measure-webpack-plugin");

const smp = new SpeedMeasurePlugin();
const config = smp.wrap({
  context: path.resolve(__dirname, "src"),
  // configurations here
  entry: {
    arkde: './custom.js',
    tinymce: './tinymce.scss'
  },
  output: {
    filename: './js/[name].js',
    // Output path using nodeJs path module
    path: path.resolve(__dirname, 'dist')
  },
  // Adding jQuery as external library
  externals: {
    jquery: 'jQuery'
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          { loader: 'css-loader', options: { url: false, sourceMap: true } }, 
          { loader: 'postcss-loader', options: { sourceMap: true } },
          { loader: 'sass-loader', options: { sourceMap: true } }
        ]
      },
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: [
          {
              loader: 'babel-loader',
              options: {
                  presets: ["@babel/preset-env"]
              }   
          }
        ]
      }
    ]
  },
  plugins: [
    
    require('autoprefixer'),
    new MiniCssExtractPlugin({filename: "/css/[name].css"}),
    new CopyWebpackPlugin({
      patterns: [
        { from: 'img/copy', to: 'img' }
        //{ from: 'img/flags', to: 'flags' }
      ],
    })    
  ]
});

module.exports = config;