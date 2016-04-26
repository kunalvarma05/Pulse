import { each, find, without } from 'lodash';
import Vue from 'vue';
import NProgress from 'nprogress';

import http from '../services/http';
import stub from '../stubs/account';

export default {
    state: {
        accounts: false,
        current: false
    },

    /**
     * Init the store.
     */
     init(accounts) {
        this.state.accounts = accounts;
    },


    /**
     * Accounts
     *
     * @return {Object}
     */
     get accounts() {
        return this.state.accounts;
    },

    /**
     * Set the accounts
     *
     * @param  {Object} account
     *
     * @return {Object}
     */
     set accounts(accounts) {
        this.state.accounts = accounts;

        return this.accounts;
    },

    /**
     * Current
     *
     * @return {Object}
     */
     get current() {
        return this.state.current;
    },

    /**
     * Set current acccount
     *
     * @param  {Object} account
     *
     * @return {Object}
     */
     set current(account) {
        this.state.current = account;

        return this.current;
    },

    /**
     * List Accounts
     */
     list(successCb = null, errorCb = null) {
        NProgress.start();
        http.get('accounts', {}, response => {
            const data = response.data;
            const accounts = data.data;

            this.accounts = accounts;

            if (successCb) {
                successCb();
            }
        }, errorCb);
    },

    /**
     * Get Account Quota
     */
     quota(id = null, successCb = null, errorCb = null) {
        NProgress.start();
        let url = 'accounts/' + id + '/manager/quota';
        http.get(url, {}, response => {
            const data = response.data;
            const quota = data.data;
            if (successCb) {
                successCb(quota);
            }
        }, errorCb);
    },

    /**
     * Get Account Info
     */
     getInfo(id = null, successCb = null, errorCb = null) {
        NProgress.start();
        let url = 'accounts/' + id + '/manager/info';
        http.get(url, {}, response => {
            const data = response.data;
            const account = data.data;

            if (successCb) {
                successCb(account);
            }
        }, errorCb);
    },

    /**
     * Create a new account
     */
     create(name, provider, code, state, cb = null) {
        NProgress.start();

        http.post('accounts/create', { name, provider, code, state }, response => {
            if (cb) {
                cb();
            }
        });
    },
};