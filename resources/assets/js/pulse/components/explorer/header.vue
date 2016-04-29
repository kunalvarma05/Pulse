<template>
    <div class="explorer-header clearfix">
        <div class="explorer-header-title">

            <div v-if="path.length" :class="'explorer-header-breadcrumb'">
                <a v-for="path in state.fileStore.path" @click="browseTo(path, $index)"> {{path.title}} </a>
            </div>
            <span v-else>
                {{title}}
            </span>

        </div>

        <nav class="nav nav-inline explorer-header-links" v-show="selectedFile">
            <a class="nav-link"><i class="fa fa-copy"></i> Copy</a>
            <a class="nav-link"><i class="fa fa-arrows"></i> Move</a>
            <a class="nav-link"><i class="fa fa-download"></i> Download</a>
            <a class="nav-link"><i class="fa fa-share"></i> Share</a>
            <a class="nav-link" @click.stop="deleteFile()"><i class="fa fa-trash"></i> Delete</a>
        </nav>

    </div>
</template>

<script>

    import fileStore from '../../stores/file';

    export default {

        props: [ 'file', 'account' ],

        data() {
            return {
                state: {
                    fileStore : fileStore.state,
                },
                title: '',
                selectedFile: ''
            };
        },

        computed: {

            /**
             * Selected File
             * @return {Object}
             */
             selectedFile() {
                return this.file;
            },

            /**
             * Current Account
             * @return {Object}
             */
             currentAccount() {
                return this.account;
            },

            /**
             * Current Explorer Path
             * @return {Array}
             */
             path() {
                return this.state.fileStore.path;
            },

            /**
             * Explorer Title
             * @return {string}
             */
             title() {
                //If no file is selected, use the name of the current account
                const title = this.currentAccount ? this.currentAccount.name : "File Explorer";
                return (this.selectedFile) ? this.selectedFile.title : title;
            },

            /**
             * Account ID
             * @return {int}
             */
             account_id() {
                return this.$route.params.account_id;
            }
        },

        methods: {

            /**
             * Browse to Path
             * @param  {Object} selectedFile Selected File
             * @param  {int} index           Path Element Index
             */
             browseTo(selectedFile, index) {
                //Breadcrumbs
                let breadcrumbs = [];

                //Browse the specified path
                fileStore.browse(this.account_id, selectedFile.id,
                    (files) => {

                        //Reset the selected file
                        this.state.fileStore.selectedFile = false;

                        //Build breadcrumbs.
                        //Keep elements upto the index of the
                        //current path and discard the rest
                        for (var i = 0; i <= index; i++) {
                            let crumb = this.path[i];
                            breadcrumbs.push(crumb);
                        };

                        //Update the current explorer path
                        this.state.fileStore.path = breadcrumbs;
                    }
                );
            },

            deleteFile() {
                const item = this.selectedFile.isFolder ? "Folder" : "File";

                swal({
                    title: "Are you sure?",
                    text: "Are you sure you wanna delete this <b>" + item + "</b>?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d9534f",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false,
                    html: true
                },
                () => {
                    const file = this.selectedFile;

                    fileStore.delete(this.currentAccount.id, this.selectedFile.id,
                        () => {
                            //File Deleted
                            swal({
                                title: item + " Deleted!",
                                type: 'success',
                                text: 'The ' + item + '<b> ' + file.title + ' </b> was deleted!',
                                allowOutsideClick: true,
                                timer: 2000,
                                html: true
                            });
                        }
                    );
                });
            }

        }
    }
</script>