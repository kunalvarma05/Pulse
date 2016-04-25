export function configRouter(router) {

    //Middlewares
    router.beforeEach(function ({ to, next, redirect}) {
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
        else {
            //Go on...
            return next();
        }
    });

    //Routes
    router.map({
        '/login': {
            name: 'login',
            guest: true,
            component: require('./components/auth/login-form.vue'),
        },
        '/dashboard': {
            name: 'dashboard',
            auth: true,
            component: require('./dashboard.vue'),
        },
    });
}