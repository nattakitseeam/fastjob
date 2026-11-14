module.exports = {
  devServer: {
    proxy: {
      '/api': {
        target: 'http://localhost/fastjob/freelance_api',
        changeOrigin: true,
        pathRewrite: { '^/api': '' }
      }
    }
  }
}