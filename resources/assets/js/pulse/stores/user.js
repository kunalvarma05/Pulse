import NProgress from 'nprogress';

import http from '../services/http';
import stub from '../stubs/user';

export default {

    /**
     * Store state
     * @type {Object}
     */
    state: {
        current: false,
        users: []
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
     * Set all Users
     * @param  {Object} users Users
     * @return {Object}      Users
     */
    set users(users) {
        this.state.users = users;
        return this.users;
    },

    /**
     * Get all Users
     *
     * @return {Object} Logged in User
     */
    get users() {
        return this.state.users;
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
     * Send Password Reset Link
     *
     * @param  {String}     email
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     */
     forgotPassword(email, successCb = null, errorCb = null) {
        NProgress.start();
        http.post('users/forgot-password', { email }, response => {
            if (successCb) {
                successCb(response);
            }
        }, errorCb);
    },

    /**
     * Reset Password
     *
     * @param  {String}     token
     * @param  {String}     password
     * @param  {String}     password_confirmation
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     */
     resetPassword(token, password, password_confirmation, successCb = null, errorCb = null) {
        NProgress.start();
        http.post('users/reset-password', { token, password, password_confirmation }, response => {
            if (successCb) {
                successCb(response);
            }
        }, errorCb);
    },

    /**
     * User List
     *
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     */
     getUsers(successCb = null, errorCb = null) {
        NProgress.start();

        http.get('users/list', {}, response => {
            const data = response.data;
            const users = data.data;

            this.state.users = users;

            if (successCb) {
                successCb(users);
            }
        }, errorCb);
    },

    /**
     * Stats
     *
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     */
     getStats(successCb = null, errorCb = null) {
        NProgress.start();

        http.get('users/stats', {}, response => {
            const data = response.data;
            const stats = data.data;

            this.stats = stats;

            if (successCb) {
                successCb(stats);
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
     * Delete User
     *
     * @param  {int}        id
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     */
     delete(id = null, successCb = null, errorCb = null) {
        NProgress.start();

        http.delete('users/delete/' + id, {}, response => {
            const data = response.data;
            const deletedUser = data.data;

            this.state.users = this.state.users.filter(function(user) {
                return user.id !== deletedUser.id;
            });

            if (successCb) {
                successCb(deletedUser);
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
     * @param  {string}     password_confirmation
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     */
     store(name, email, username, password, password_confirmation, successCb = null, errorCb = null) {
        NProgress.start();

        http.post('users/create', { name, email, username, password, password_confirmation },
            response => {
                if (successCb) {
                    successCb(response);
                }
            },
            response => {
                const data = response.data;
                const errors = data.errors;

                if(errorCb) {
                    errorCb(errors);
                }
            }
        );
    },
};