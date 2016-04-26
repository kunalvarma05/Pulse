import { each, find, without } from 'lodash';
import Vue from 'vue';
import NProgress from 'nprogress';

import http from '../services/http';
import stub from '../stubs/file';

export default {
    state: {
        selected: false,
        files: false
    },

    /**
     * Init the store.
     *
     * @param {Object}          selectedFile The selected file.
     */
     init(selectedFile) {
        this.selected = selectedFile;
    },

    /**
     * The selected file.
     *
     * @return {Object}
     */
     get selected() {
        return this.state.selected;
    },

    /**
     * Set the selected file.
     *
     * @param  {Object} file
     *
     * @return {Object}
     */
     set selected(file) {
        this.state.selected = file;

        return this.selected;
    },

    /**
     * Browse
     */
     browse(account, path = null, successCb = null, errorCb = null) {
        NProgress.start();
        let url = "accounts/" + account + "/manager/browse";
        let data = path === null ? {} : { path };
        http.get(url, data, response => {
            const data = response.data;
            const files = data.data;

            this.state.files = files;

            if (successCb) {
                successCb();
            }
        }, errorCb);
    },
};