import { each, find, without } from 'lodash';
import Vue from 'vue';
import NProgress from 'nprogress';

import http from '../services/http';

export default {
    state: {
        current: {
            id: 0,
            name: '',
            email: '',
            picture: ''
        },
    },

    /**
     * Init the store.
     *
     * @param {Object}          currentUser The current user.
     */
     init(currentUser) {
        this.current = currentUser;
    },

    /**
     * The current user.
     *
     * @return {Object}
     */
     get current() {
        return this.state.current;
    },

    /**
     * Set the current user.
     *
     * @param  {Object} user
     *
     * @return {Object}
     */
     set current(user) {
        this.state.current = user;

        return this.current;
    },

    /**
     * Log a user in.
     *
     * @param  {String}     email
     * @param  {String}     password
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     */
     login(email, password, successCb = null, errorCb = null) {
        NProgress.start();
        http.post('users/authorize', { email, password }, () => {
            if (successCb) {
                successCb();
            }
        }, errorCb);
    },

    /**
     * Show User Profile
     *
     * @param  {int}     id
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     */
     profile(id = null, successCb = null, errorCb = null) {
        NProgress.start();
        http.post('users/show/' + id, {}, () => {
            if (successCb) {
                successCb();
            }
        }, errorCb);
    },

    /**
     * Log the current user out.
     *
     * @param  {Function} cb The callback.
     */
     logout(cb = null) {
        this.state.current = {
            id: 0,
            name: '',
            email: '',
            picture: ''
        };
        if(cb) {
            cb();
        }
    },

    /**
     * Stores a new user into the database.
     *
     * @param  {string}     name
     * @param  {string}     email
     * @param  {string}     password
     * @param  {?Function}  cb
     */
     store(name, email, username, password, cb = null) {
        NProgress.start();

        http.post('user', { name, email, username, password }, response => {

            if (cb) {
                cb();
            }
        });
    },

    /**
     * Delete a user.
     *
     * @param  {Object}     user
     * @param  {?Function}  cb
     */
     destroy(user, cb = null) {
        NProgress.start();

        http.delete(`user/${user.id}`, {}, () => {
            this.all = without(this.all, user);
            if (cb) {
                cb();
            }
        });
    },
};