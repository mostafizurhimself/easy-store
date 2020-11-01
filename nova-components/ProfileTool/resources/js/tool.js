Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'profile-tool',
            path: '/profile-tool',
            component: require('./components/Tool'),
        },
    ])
})
