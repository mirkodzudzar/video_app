var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/assets/')
    // public path used by the web server to access the output path
    .setPublicPath('/assets')

    // .addEntry('app', './assets/js/app.js')
    .addStyleEntry('css/dashboard', ['./assets/css/dashboard.css'])
    .addStyleEntry('css/login', ['./assets/css/login.css'])

;

module.exports = Encore.getWebpackConfig();