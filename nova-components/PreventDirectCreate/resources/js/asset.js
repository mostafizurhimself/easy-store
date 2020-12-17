Nova.booting((Vue, router, store) => {
    //Fabrics Section
    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/fabric-purchase-items/new" &&
            (to.query.viaResource != 'fabric-purchase-orders' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/fabric-receive-items/new" &&
            (to.query.viaResource != 'fabric-purchase-items' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });


    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/fabric-return-items/new" &&
            (to.query.viaResource != 'fabric-return-invoices' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });


    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/fabric-transfer-items/new" &&
            (to.query.viaResource != 'fabric-transfer-invoices' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/fabric-transfer-receive-items/new" &&
            (to.query.viaResource != 'fabric-transfer-items' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    //Material Section
    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/material-purchase-items/new" &&
            (to.query.viaResource != 'material-purchase-orders' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/material-receive-items/new" &&
            (to.query.viaResource != 'material-purchase-items' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/material-return-items/new" &&
            (to.query.viaResource != 'material-return-invoices' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/material-transfer-items/new" &&
            (to.query.viaResource != 'material-transfer-invoices' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/material-transfer-receive-items/new" &&
            (to.query.viaResource != 'material-transfer-items' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    //Asset Section
    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/asset-purchase-items/new" &&
            (to.query.viaResource != 'asset-purchase-orders' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/asset-receive-items/new" &&
            (to.query.viaResource != 'asset-purchase-items' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/asset-return-items/new" &&
            (to.query.viaResource != 'asset-return-invoices' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/asset-requisition-items/new" &&
            (to.query.viaResource != 'asset-requisitions' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/asset-distribution-items/new" &&
            (to.query.viaResource != 'asset-distribution-invoices' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/asset-distribution-receive-items/new" &&
            (to.query.viaResource != 'asset-distribution-items' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    //Service Section
    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/service-dispatches/new" &&
            (to.query.viaResource != 'service-invoices' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/service-receives/new" &&
            (to.query.viaResource != 'service-dispatches' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/service-transfer-items/new" &&
            (to.query.viaResource != 'service-transfer-invoices' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/service-transfer-receive-items/new" &&
            (to.query.viaResource != 'service-transfer-items' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });

    //Finishing Section
    router.beforeEach((to, from, next) => {
        if (
            to.path === "/resources/finishings/new" &&
            (to.query.viaResource != 'finishing-invoices' || to.query.viaResourceId == null)
        ) {
            router.push({ name: "403" });

            return;
        }

        next();
    });


});

