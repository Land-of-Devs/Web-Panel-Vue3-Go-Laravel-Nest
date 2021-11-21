module.exports = {
  devServer: {
    disableHostCheck: true,
    sockPath: "/dev/sockjs-node",
    progress: false
  },

  publicPath: process.env.NODE_ENV === 'production' ? '/' : '/dev/'
};
