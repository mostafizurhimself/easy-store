Nova.booting((Vue, router, store) => {


router.beforeEach((to, from, next) => {
    if (to.path === '/resources/asset-purchase-items/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});

//   router.addRoutes([
//     {
//       name: 'prevent-direct-create-tool',
//       path: '/prevent-direct-create-tool',
//       component: require('./components/Tool'),
//     },
//   ])
})
