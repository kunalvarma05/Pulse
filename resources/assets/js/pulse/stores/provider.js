import { each, find, without } from 'lodash';
import Vue from 'vue';
import NProgress from 'nprogress';

import http from '../services/http';
import stub from '../stubs/provider';

export default {
    state: {
        providers: [],
        authUrl: null
    },

    /**
     * Init the store.
     */
     init(providers) {
        this.state.providers = providers;
    },

    /**
     * List Accounts
     */
     list(successCb = null, errorCb = null) {
        NProgress.start();
        http.get('providers', {}, response => {
            const data = response.data;
            const providers = data.data;

            this.state.providers = providers;

            if (successCb) {
                successCb();
            }
        }, errorCb);
    },

    /**
     * Get Account Quota
     */
     authUrl(provider = null, successCb = null, errorCb = null) {
        NProgress.start();
        let url = 'providers/auth-url';

        http.get(url, { provider }, response => {

            const data = response.data;
            const authUrl = data.url;

            this.state.authUrl = authUrl;

            if (successCb) {
                successCb(authUrl);
            }
        }, errorCb);
    },
};