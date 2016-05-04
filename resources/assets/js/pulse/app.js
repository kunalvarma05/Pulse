import Vue from 'vue';
import ls from './services/ls';
import NProgress from 'nprogress';
//Routes
import { configRouter } from './routes.js';

//Debug ON
Vue.config.debug = true;

//Main App Instance
const app = require('./app.vue');

//Use Vue-Resource
require('./config/resource');

//Router
var VueRouter = require('vue-router');
Vue.use(VueRouter);

//Init Router
const router = new VueRouter({
    saveScrollPosition: true,
    linkActiveClass: 'active'
});

//Configure Router
configRouter(router);

//Kickoff!
router.start(app, 'body');