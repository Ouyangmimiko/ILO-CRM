// vue.config.js
const { defineConfig } = require('@vue/cli-service')
console.log('vue.config.js is loaded');
module.exports = defineConfig({
    configureWebpack: {
        resolve: {
            alias: {
                '@': require('path').join(__dirname, 'src'),
            },
        },
    },
    devServer: {
        compress: false,
        proxy: {
            '/api/*': {
              target: 'http://localhost:8000', //   Laravel API location
              changeOrigin: true,
                logLevel: 'debug',
            },
        } ,
    },
})
