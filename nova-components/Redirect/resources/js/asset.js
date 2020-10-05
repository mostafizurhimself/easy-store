Nova.booting((Vue, router, store) => {

    router.beforeEach((to, from, next) => {

        // Fabrics Section
        if (to.params.resourceName === 'fabric-purchase-orders' && to.params.lens === 'purchase-items') {
            router.push({'name' : 'index', params: {resourceName : 'fabric-purchase-items'}});

            return;
        }

        if (to.params.resourceName === 'fabric-purchase-orders' && to.params.lens === 'receive-items') {
            router.push({'name' : 'index', params: {resourceName : 'fabric-receive-items'}});

            return;
        }

        if (to.params.resourceName === 'fabric-return-invoices' && to.params.lens === 'return-items') {
            router.push({'name' : 'index', params: {resourceName : 'fabric-return-items'}});

            return;
        }

        // Material Section
        if (to.params.resourceName === 'material-purchase-orders' && to.params.lens === 'purchase-items') {
            router.push({'name' : 'index', params: {resourceName : 'material-purchase-items'}});

            return;
        }

        if (to.params.resourceName === 'material-purchase-orders' && to.params.lens === 'receive-items') {
            router.push({'name' : 'index', params: {resourceName : 'material-receive-items'}});

            return;
        }

        if (to.params.resourceName === 'material-return-invoices' && to.params.lens === 'return-items') {
            router.push({'name' : 'index', params: {resourceName : 'material-return-items'}});

            return;
        }



        // Asset Section
        if (to.params.resourceName === 'assets-purchase-orders' && to.params.lens === 'purchase-items') {
            router.push({'name' : 'index', params: {resourceName : 'assets-purchase-items'}});

            return;
        }

        if (to.params.resourceName === 'assets-purchase-orders' && to.params.lens === 'receive-items') {
            router.push({'name' : 'index', params: {resourceName : 'assets-receive-items'}});

            return;
        }

        if (to.params.resourceName === 'asset-return-invoices' && to.params.lens === 'return-items') {
            router.push({'name' : 'index', params: {resourceName : 'asset-return-items'}});

            return;
        }

        if (to.params.resourceName === 'asset-requisitions' && to.params.lens === 'requisition-items') {
            router.push({'name' : 'index', params: {resourceName : 'asset-requisition-items'}});

            return;
        }

        if (to.params.resourceName === 'asset-distribution-invoices' && to.params.lens === 'distribution-items') {
            router.push({'name' : 'index', params: {resourceName : 'asset-distribution-items'}});

            return;
        }


        //Service Seciton
        if (to.params.resourceName === 'service-invoices' && to.params.lens === 'dispatch-items') {
            router.push({'name' : 'index', params: {resourceName : 'service-dispatches'}});

            return;
        }

        if (to.params.resourceName === 'service-invoices' && to.params.lens === 'receive-items') {
            router.push({'name' : 'index', params: {resourceName : 'service-receives'}});

            return;
        }


        next();
    });

})// Nova Asset JS
