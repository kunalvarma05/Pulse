import { each, find, without } from 'lodash';
import Vue from 'vue';
import NProgress from 'nprogress';

import http from '../services/http';
import stub from '../stubs/file';

export default {
    state: {
        path: [],
        selected: false,
        files: false
    },

    /**
     * Init the store.
     *
     * @param {Object}          selectedFile The selected file.
     */
     init(selectedFile, path) {
        this.selected = selectedFile;
        this.path = path;
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
     * The selected path
     *
     * @return {Object}
     */
     get path() {
        return this.state.path;
    },

    /**
     * Set the selected path
     *
     * @param  {Array} path
     *
     * @return {Object}
     */
     set path(path) {
        this.state.path = path;

        return this.path;
    },

    /**
     * The files
     *
     * @return {Object}
     */
     get files() {
        return this.state.files;
    },

    /**
     * Set the files
     *
     * @param  {Array} files
     *
     * @return {Object}
     */
     set files(files) {
        this.state.files = files;

        return this.files;
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
                successCb(files);
            }
        }, errorCb);
    },
};