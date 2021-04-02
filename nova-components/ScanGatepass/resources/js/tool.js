Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'scan-gatepass',
      path: '/scan-gatepass',
      component: require('./components/Tool'),
    },
  ])
})
