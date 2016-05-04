<template>
    <div class="explorer" :class="{ 'has-sidebar': true }" id="explorer">

        <div class="explorer-wrapper" @click.stop="deSelectFile()">
            <explorer-header :file.sync='selectedFile' :account.sync='currentAccount'></explorer-header>

            <div class="explorer-content">
                <div class="container-fluid">
                    <div class="row explorer-items">

                        <explorer-file v-for="fileItem in state.fileStore.files" :file='fileItem' :index='$index'></explorer-file>

                        <div class="empty-state text-center" v-show='!hasFiles'>
                            <h1>
                                <span class="fa fa-file-word-o first"></span>
                                <span class="fa fa-folder leader"></span>
                                <span class="fa fa-file-zip-o last"></span>
                            </h1>
                            <h4>No files to show!</h4>
                        </div>

                    </div>
                </div>
            </div>

            <sidebar :file.sync='selectedFile' :account.sync='currentAccount'></sidebar>

            <share-file-modal></share-file-modal>

            <upload-file-modal :account.sync="currentAccount"></upload-file-modal>
        </div>

        <div class="dropup explorer-new-item">
            <a class="btn explorer-new-item-button btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-plus"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right explorer-new-item-menu">
                <a class="dropdown-item" @click.stop="uploadFile">
                    <i class="fa fa-cloud-upload"></i> Upload
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" @click.stop="createFolder()">
                    <i class="fa fa-folder"></i> Create Folder
                </a>
            </div>

        </div>
    </div>
</template>

<script>

    import fileStore from '../../stores/file';
    import accountStore from '../../stores/account';

    import explorerHeader from './header.vue';
    import explorerFile from './file.vue';
    import sidebar from '../sidebar/index.vue';
    import shareFileModal from '../modals/sharefile.vue';
    import uploadFileModal from '../modals/uploadfile.vue';


    export default {

        components: { explorerHeader, explorerFile, sidebar, shareFileModal, uploadFileModal },

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
             * Explorer/Folder Has Files or Not
             * @return boolean
             */
             hasFiles() {
                return this.state.fileStore.files.length;
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
            },

            /**
             * Current Location
             * @return string
             */
             currentLocation() {
                return this.state.fileStore.currentLocation;
            }

        },

        methods: {

            /**
             * Fire DeSelect File Event
             */
             deSelectFile() {
                this.$broadcast('explorer:deSelectFile');
            },

            /**
             * Create Folder
             */
             createFolder() {
                swal({
                    title: "Create Folder",
                    type: "input",
                    confirmButtonColor: "#2b90d9",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    animation: "slide-from-top",
                    inputPlaceholder: "New Folder Title..."
                },
                title => {

                    if (title === false) return false;

                    if (title === "") {
                        swal.showInputError("Folder name cannot be blank!");
                        return false
                    }

                    //Create Folder
                    fileStore.createFolder(this.currentAccount.id, title, this.currentLocation,
                        folder => {
                            swal("Folder Created!", "The folder, " + title + " was created!", "success");
                        }
                    );
                });
            },

            uploadFile() {
                //Broadcast
                this.$broadcast('file:upload', { account: this.currentAccount, location: this.currentLocation });
            }

        },

        events: {

            "file:share"(data) {
                //Broadcast to all childrens
                this.$broadcast('file:share', { file: data.file, link: data.link });
            },

            "file:uploaded"(data) {
                //If the last file was uploaded to the current account
                if(this.currentAccount.id === this.state.fileStore.lastUploadAccount.id) {
                    const file = data.file;

                    //Add file to the list
                    this.state.fileStore.files.unshift(file);

                    this.state.fileStore.selected = file;
                }
                //Broadcast to all childrens
                this.$broadcast('file:uploaded', data);
            },

            "file:queued"(data) {
                //Set the last upload account
                this.state.fileStore.lastUploadAccount = this.currentAccount;
                //Broadcast to all childrens
                this.$broadcast('file:queued', data);
            },

            "file:canceled"(data) {
                //Broadcast to all childrens
                this.$broadcast('file:canceled', data);
            },

            "file:removed"(data) {
                //Broadcast to all childrens
                this.$broadcast('file:removed', data);
            },

        }

    }
</script>