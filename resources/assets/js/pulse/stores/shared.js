import http from '../services/http';
import userStore from './user';

export default {
    /**
     * Store state
     * @type {Object}
     */
     state: {
        currentUser: userStore.current,
    },

    /**
     * Set the current user
     *
     * @param  {Object} user Logged in User
     * @return {Object}      Logged in User
     */
     set currentUser(user) {
        this.state.currentUser = user;
        return this.currentUser;
    },

    /**
     * Get the current user
     *
     * @return {Object} Logged in User
     */
     get currentUser() {
        return this.state.currentUser;
    },

    /**
     * Initialize the store
     *
     * @param  {?Function} successCb Success Callback
     * @param  {?Function} errorCb   Error Callback
     * @return {Promise}
     */
     init(successCb = null, errorCb = null) {
        //Reset the store state
        this.reset();

        //Initialize
        return http.get('users/initialize', {}, response => {
            const data = response.data;
            const user = data.data;

            //Set the current user
            this.currentUser = user;

            //Initialize the user store
            userStore.init(this.currentUser);

            if (successCb) {
                successCb(response);
            }

        }, errorCb);
    },

    /**
     * Reset the store
     */
     reset() {
        this.state.currentUser = false;
    },
};