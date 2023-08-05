const path = require('path')

module.exports = {
    entry: './assets/scripts/App.js',
    output: {
        filename: 'bundled.js',
        path: path.resolve(__dirname)
    },
    mode: 'development',
    watch: true
}