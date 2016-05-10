export function configRouter(router) {

    //Routes
    router.map({
        '/': {
            name: 'index',
            guest: true,
            component: require('./components/splash.vue'),
        },
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
        '/forgot-password': {
            name: 'forgot-password',
            guest: true,
            component: require('./components/auth/forgot-password-form.vue'),
        },
        '/reset-password': {
            name: 'reset-password',
            guest: true,
            component: require('./components/auth/reset-password-form.vue'),
        },
        '/dashboard': {
            name: 'dashboard',
            auth: true,
            component: require('./components/dashboard/index.vue'),

            subRoutes: {
                '/connect-account': {
                    name: 'connect-account',
                    auth: true,
                    component: require('./components/account/connect.vue'),
                },
                '/accounts/:account_id': {
                    name: 'account-explorer',
                    auth: true,
                    component: require('./components/explorer/index.vue'),
                },
                '/auth-callback/:provider' : {
                    name: 'auth-callback',
                    auth: true,
                    component: require('./components/account/create.vue')
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