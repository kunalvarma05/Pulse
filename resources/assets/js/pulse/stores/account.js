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
        http.get('accounts', { with: 'provider' }, response => {
            const data = response.data;
            const accounts = data.data;

            this.accounts = accounts;

            if (successCb) {
                successCb();
            }
        }, errorCb);
    },

    /**
     * Get Account Info
     * @param  {int}  id        Account ID
     * @param  {Boolean} quota     If true, account quota will be returned
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     * @return {Promise}
     */
     getInfo(id = null, quota = false, successCb = null, errorCb = null) {
        NProgress.start();
        let url = 'accounts/' + id + '/manager/info';

        return http.get(url, { quota }, response => {
            const data = response.data;
            const account = data.data;

            if (successCb) {
                successCb(account);
            }
        }, errorCb);
    },

    /**
     * Create Account
     * @param  {string} name
     * @param  {string} provider
     * @param  {string} code      Auth Code
     * @param  {string} state     CSRF State
     * @param  {?Function} successCb
     * @param  {?Function} errorCb
     * @return {Promise}
     */
     create(name, provider, code, state, successCb = null, errorCb = null) {
        NProgress.start();

        return http.post('accounts/create', { name, provider, code, state },
            response => {
                const data = response.data;
                const account = data.data;

                this.accounts.unshift(account);

                if (successCb) {
                    successCb(account);
                }
            },
            response => {
                const data = response.data;
                const errors = data.errors;

                if (errorCb) {
                    errorCb(errors);
                }

            }
        );
    },
};