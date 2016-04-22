import { assign } from 'lodash';

import http from '../services/http';
import userStore from './user';

export default {
    state: {
        currentUser: null,
    },

    init(successCb = null, errorCb = null) {
        this.reset();

        http.get('users/initialize', response => {
            const data = response.data;
            const user = data.data;

            this.state.currentUser = user;

            userStore.init(this.state.currentUser);

            if (successCb) {
                successCb();
            }
        }, errorCb);
    },

    reset() {
        this.state.currentUser = null;
    },
};