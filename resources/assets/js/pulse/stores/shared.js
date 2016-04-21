import { assign } from 'lodash';

import http from '../services/http';
import userStore from './user';

export default {
    state: {
        currentUser: null,
    },

    init(successCb = null, errorCb = null) {
        this.reset();

        http.get('data', response => {
            const data = response.data;
            console.log(data);

            assign(this.state, data);

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