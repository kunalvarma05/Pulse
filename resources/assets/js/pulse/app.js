import Vue from 'vue';
import ls from './services/ls';
import NProgress from 'nprogress';
//Debug ON
Vue.config.debug = true;
//Use Vue-Resource
require('./config/resource');

//Main App Component
const app = require('./app.vue');
//Dashboard Component
const dashboard = require('./dashboard.vue');
//Login Component
const login = require('./components/auth/login-form.vue');
//Explorer
const explorer = require('./components/explorer/index.vue');

//User Store
import userStore from './stores/user';
import sharedStore from './stores/shared';

//Router
var VueRouter = require('vue-router');
Vue.use(VueRouter);
var router = new VueRouter();

// router.beforeEach(function (transition) {
//     if (transition.to.auth && !router.app.authenticated) {
//         transition.redirect({ name: 'login'});
//     } else if (transition.to.guest && router.app.authenticated) {
//         transition.redirect({ name: 'dashboard'});
//     } else {
//         transition.next();
//     }
// });

router.map({
    '/': {
        name: 'index',
        auth: false,
        guest: true,
        component: app
    },
    '/login': {
        name: 'login',
        guest: true,
        component: login,
    },

    '/dashboard': {
        name: 'dashboard',
        auth: true,
        component: dashboard,

        subRoutes: {
            '/account/:account_id': {
                name: 'explorer',
                component: explorer
            }
        }
    },
});

router.start(app, 'body');