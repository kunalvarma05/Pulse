import Vue from 'vue';
import ls from '../services/ls';
import NProgress from 'nprogress';

var VueResource = require('vue-resource');

Vue.use(VueResource);

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

        if (r.status === 401) {
            app.logout();
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