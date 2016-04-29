<template>
    <div class="sidebar-header animated slideInRight">
        <input type="text" class="form-control input-sm" placeholder="Title" v-model='fileTitle' @keydown.enter.stop="renameFile">
    </div>

    <div class="sidebar-body">
        <div class="sidebar-items">
            <div class="sidebar-item">
                <div class="sidebar-item-body has-details">
                    <div class="item-detail" v-show='!file.isFolder'>
                        <span class="item-detail-title">Type</span>
                        <span class="item-detail-value">{{ file.mimeType }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Size</span>
                        <span class="item-detail-value">{{ file.size }}</span>
                    </div>
                    <div class="item-detail" v-show='file.owners'>
                        <span class="item-detail-title">Owner</span>
                        <span class="item-detail-value">{{ file.owners }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Path</span>
                        <span class="item-detail-value">{{ file.path }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Modified</span>
                        <span class="item-detail-value">{{ file.modified }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Vue from 'vue';
    import fileStore from '../../stores/file';

    export default {

        props: [ 'file', 'account' ],

        data() {
            return {
                state: {
                    fileStore: fileStore.state
                }
            };
        },

        computed: {

            /**
             * Current Account
             * @return {Object}
             */
             currentAccount() {
                return this.account;
            },

            /**
             * Selected File
             * @return {Object}
             */
             selectedFile: {
                get() {
                    return this.file;
                },

                set(file) {
                    this.file = file;
                }
            },

            /**
             * Selected File Title
             * @return {string}
             */
             fileTitle: {

                get() {
                    return this.file.title;
                },

                set(title) {
                    this.file.title = title;
                }
            }

        },

        methods: {

            /**
             * Rename the selected file
             */
             renameFile() {
                //Rename the file
                fileStore.rename(this.currentAccount.id, this.selectedFile.id, this.selectedFile.title);
            }

        }

    }
</script>