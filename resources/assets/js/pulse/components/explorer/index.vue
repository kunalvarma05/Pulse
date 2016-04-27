<template>
    <div class="explorer" :class="{ 'has-sidebar': true }" id="explorer">

        <explorer-header :file.sync='selectedFile' :account.sync='currentAccount'></explorer-header>

        <div class="explorer-content">
            <div class="container-fluid">
                <div class="row explorer-items">

                    <explorer-file v-for="fileItem in state.fileStore.files" :file='fileItem' :index='$index'></explorer-file>

                    <h4 class="text-center" v-show='!state.fileStore.files' align="center">No files to show!</h4>

                </div>
            </div>
        </div>

        <sidebar :file.sync='selectedFile' :account.sync='currentAccount'></sidebar>

        <connect-account></connect-account>

    </div>
</template>

<script>

    import fileStore from '../../stores/file';
    import accountStore from '../../stores/account';

    import explorerHeader from './header.vue';
    import explorerFile from './file.vue';
    import connectAccount from '../modals/connect-account.vue';
    import sidebar from '../sidebar/index.vue';


    export default {

        components: { explorerHeader, explorerFile, sidebar, connectAccount },

        data() {
            return {
                state: {
                    fileStore : fileStore.state,
                    accountStore : accountStore.state,
                },
                title: "File Explorer"
            };
        },

        route: {

            data() {

                //Initialize the File Store
                fileStore.init(false, []);

                //Get Account Info with quota
                accountStore.getInfo(this.account_id, true,
                    (account) => {
                        //Set the current account
                        this.state.accountStore.current = account;
                    }
                    );

                //Browse Files
                fileStore.browse(this.account_id);
            }
        },

        computed: {

            /**
             * Account Id
             * @return {int}
             */
             account_id() {
                return this.$route.params.account_id;
            },

            /**
             * Selected File
             * @return {Object}
             */
             selectedFile() {
                return this.state.fileStore.selected;
            },

            /**
             * Current Account
             * @return {Object}
             */
             currentAccount() {
                return this.state.accountStore.current;
            }

        },

    }
</script>