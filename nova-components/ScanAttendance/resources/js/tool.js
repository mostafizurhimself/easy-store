Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'scan-attendance',
      path: '/scan-attendance',
      component: require('./components/Tool'),
    },
  ])
})
