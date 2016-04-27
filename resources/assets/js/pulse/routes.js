export function configRouter(router) {

    //Routes
    router.map({
        '/login': {
            name: 'login',
            guest: true,
            component: require('./components/auth/login-form.vue'),
        },
        '/signup': {
            name: 'signup',
            guest: true,
            component: require('./components/auth/signup-form.vue'),
        },
        '/dashboard': {
            name: 'dashboard',
            auth: true,
            component: require('./components/dashboard/index.vue'),

            subRoutes: {
                '/accounts/:account_id': {
                    name: 'account-explorer',
                    auth: true,
                    component: require('./components/explorer/index.vue'),
                }
            }
        },
    });

    //BeforeEach Route Hook
    router.beforeEach(function ({ to, redirect, next }) {
        //If route requires authentication
        //and the user is not logged in
        if (to.auth && !router.app.authenticated) {
            return redirect({ name: 'login' });
        }
        //If the route requires a guest
        //and the user is logged in
        else if(to.guest && router.app.authenticated) {
            return redirect({ name: 'dashboard' });
        }
        //Go on...
        return next();
    });

}