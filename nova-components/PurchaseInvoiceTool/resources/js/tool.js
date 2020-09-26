Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'purchase-invoice-tool',
      path: '/purchase-invoice-tool',
      component: require('./components/Tool'),
    },
  ])
})
