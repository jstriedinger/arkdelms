const merge = require('webpack-merge');
const common = require('./webpack.common.js');
const path = require('path');
const glob = require('glob-all');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const PurifyCSSPlugin = require("purifycss-webpack");

module.exports = merge(common, {
	mode: 'production',
	module: {
	    rules: [
	      {
	        test: /\.(gif|png|jpe?g|svg)$/i,
	        include: [
	              path.resolve(__dirname, "src/img/opt")
	          ],
	        use: [
	        {loader: 'file-loader', options:{name: '[name].[ext]',outputPath: 'img', } },
	        {
	          loader: 'image-webpack-loader',
	          options: {
	            mozjpeg: {
	              progressive: true,
	              quality: 75
	            },
	            // optipng.enabled: false will disable optipng
	            optipng: {
	              enabled: true,
	            }
	          }
	        }
	      ]
	      }
	    ]
	},
	optimization: {
	    minimizer: [new TerserPlugin({ cache: true, parallel: true, terserOptions: { output: {comments: false} } })]
	  },
	plugins: [
		new OptimizeCssAssetsPlugin({
	      assetNameRegExp: /\.css$/g,
	      cssProcessor: require('cssnano'),
	      cssProcessorPluginOptions: {
	        preset: ['default', { discardComments: { removeAll: true } }],
	      },
	      canPrint: true
	    })    
	]
});