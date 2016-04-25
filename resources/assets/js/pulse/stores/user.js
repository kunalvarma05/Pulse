import NProgress from 'nprogress';

import http from '../services/http';
import stub from '../stubs/user';

export default {

    /**
     * Store state
     * @type {Object}
     */
    state: {
        current: stub,
    },

    /**
     * Set the Current User
     * @param  {Object} user Logged in User
     * @return {Object}      Logged in User
     */
    set current(user) {
        this.state.current = user;
        return this.current;
    },

    /**
     * Get the Current User
     *
     * @return {Object} Logged in User
     */
    get current() {
        return this.state.current;
    },

    /**
     * Initialize the store
     *
     * @param {Object} currentUser The Current User
     */
     init(currentUser) {
        this.state.current = currentUser;
    },

    /**
     * Log in a user
     *
     * @param  {String}     email
     * @param  {String}     password
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     */
     login(email, password, successCb = null, errorCb = null) {
        NProgress.start();
        http.post('users/authorize', { email, password }, response => {
            if (successCb) {
                successCb(response);
            }
        }, errorCb);
    },

    /**
     * Show User Profile
     *
     * @param  {int}        id
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
     * Log the current user out
     *
     * @param  {?Function} cb The callback.
     */
     logout(cb = null) {
        this.state.current = false;

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

        http.post('users/create', { name, email, username, password }, response => {
            if (cb) {
                cb();
            }
        });
    },
};