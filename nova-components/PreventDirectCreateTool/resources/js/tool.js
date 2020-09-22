Nova.booting((Vue, router, store) => {


//Fabrics Section
router.beforeEach((to, from, next) => {
    if (to.path === '/resources/fabric-purchase-items/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});


router.beforeEach((to, from, next) => {
    if (to.path === '/resources/fabric-receive-items/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});


//Material Section
router.beforeEach((to, from, next) => {
    if (to.path === '/resources/material-purchase-items/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});


router.beforeEach((to, from, next) => {
    if (to.path === '/resources/material-receive-items/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});


//Asset Section
router.beforeEach((to, from, next) => {
    if (to.path === '/resources/asset-purchase-items/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});



router.beforeEach((to, from, next) => {
    if (to.path === '/resources/asset-receive-items/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});

router.beforeEach((to, from, next) => {
    if (to.path === '/resources/asset-requisition-items/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});

router.beforeEach((to, from, next) => {
    if (to.path === '/resources/asset-distribution-items/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});

router.beforeEach((to, from, next) => {
    if (to.path === '/resources/asset-distribution-receive-items/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});


//Service Section
router.beforeEach((to, from, next) => {
    if (to.path === '/resources/service-dispatches/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});


router.beforeEach((to, from, next) => {
    if (to.path === '/resources/service-receives/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
        router.push({'name' : '403'});

        return;
    }

    next();
});

//Finishing Section
router.beforeEach((to, from, next) => {
    if (to.path === '/resources/finishings/new' &&  (to.query.viaResource == null || to.query.viaResourceId == null)) {
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
