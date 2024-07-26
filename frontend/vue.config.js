// vue.config.js
module.exports = {
    devServer: {
        proxy: {
            '/backend': {
              target: 'http://127.0.0.1:8000', //   Laravel API location
              changeOrigin: true,
            },
        } ,
    },
}
