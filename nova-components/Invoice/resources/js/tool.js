Nova.booting((Vue, router, store) => {
Vue.config.devtools = true;

  router.addRoutes([
    {
      name: 'invoice',
      path: '/invoice',
      component: require('./components/Tool'),
    },
  ])
})
