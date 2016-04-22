import { each, find, without } from 'lodash';
import Vue from 'vue';
import NProgress from 'nprogress';

import http from '../services/http';
import stub from '../stubs/file';

export default {
    state: {
        current: false,
        files: false
    },

    /**
     * Init the store.
     *
     * @param {Object}          currentFile The current file.
     */
     init(currentFile) {
        this.current = currentFile;
    },

    /**
     * The current file.
     *
     * @return {Object}
     */
     get current() {
        return this.state.current;
    },

    /**
     * Set the current file.
     *
     * @param  {Object} file
     *
     * @return {Object}
     */
     set current(file) {
        this.state.current = file;

        return this.current;
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

    /**
     * Show file Profile
     *
     * @param  {int}     id
     * @param  {?Function}  successCb
     * @param  {?Function}  errorCb
     */
     profile(id = null, successCb = null, errorCb = null) {
        NProgress.start();
        http.post('files/show/' + id, {}, () => {
            if (successCb) {
                successCb();
            }
        }, errorCb);
    },

    /**
     * Log the current file out.
     *
     * @param  {Function} cb The callback.
     */
     logout(cb = null) {
        this.state.current = {
            stub
        };
        if(cb) {
            cb();
        }
    },

    /**
     * Stores a new file into the database.
     *
     * @param  {string}     name
     * @param  {string}     email
     * @param  {string}     password
     * @param  {?Function}  cb
     */
     store(name, email, filename, password, cb = null) {
        NProgress.start();

        http.post('file', { name, email, filename, password }, response => {
            if (cb) {
                cb();
            }
        });
    },

    /**
     * Delete a file.
     *
     * @param  {Object}     file
     * @param  {?Function}  cb
     */
     destroy(file, cb = null) {
        NProgress.start();

        http.delete(`file/${file.id}`, {}, () => {
            this.all = without(this.all, file);
            if (cb) {
                cb();
            }
        });
    },
};