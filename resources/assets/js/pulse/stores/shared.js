import { assign } from 'lodash';

import http from '../services/http';
import userStore from './user';

export default {
    state: {
        currentUser: userStore.current,
    },

    set currentUser(user) {
        this.state.currentUser = user;
        return this.currentUser;
    },

    get currentUser() {
        return this.state.currentUser;
    },

    init(successCb = null, errorCb = null) {
        this.reset();

        http.get('users/initialize', {}, response => {
            const data = response.data;
            const user = data.data;

            this.currentUser = user;
            userStore.init(this.currentUser);

            if (successCb) {
                successCb();
            }
        }, errorCb);
    },

    reset() {
        this.state.currentUser = false;
    },
};