const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true,
  publicPath: process.env.NODE_ENV === 'production'
    ? '/currencyConverter/frontend-currencyconvertor/' // For production 
    : '/', // For development (localhost)
})
