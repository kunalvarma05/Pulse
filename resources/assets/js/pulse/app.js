import Vue from 'vue';
import ls from './services/ls';
import NProgress from 'nprogress';

const app = new Vue(require('./app.vue'));

//Vue.config.debug = false;
Vue.use(require('vue-resource'));
Vue.http.options.root = '/api';
Vue.http.interceptors.push({
    request(r) {
        const token = ls.get('token');

        if (token) {
            Vue.http.headers.common.Authorization = `Bearer ${token}`;
        }

        return r;
    },

    response(r) {
        NProgress.done();

        if (r.status === 400 || r.status === 401) {
            if (r.request.method !== 'POST' && r.request.url !== 'users/logout') {
                // This is not a failed login. Log out then.
                app.logout();
            }
        }

        if (r.headers && r.headers.Authorization) {
            ls.set('token', r.headers.Authorization);
        }

        if (r.data && r.data.token && r.data.token.length > 10) {
            ls.set('token', r.data.token);
        }

        return r;
    },
});

app.$mount('body');