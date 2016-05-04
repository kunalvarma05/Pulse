<template>
    <div class="explorer-header clearfix">
        <div class="explorer-header-title">
            <a @click="browseRoot()" class="explorer-header-icon">
                <i class="fa fa-home"></i>
            </a>
            <div v-if="path.length" :class="'explorer-header-breadcrumb'">
                <a v-for="path in state.fileStore.path" @click="browseTo(path, $index)"> {{path.title}} </a>
            </div>
            <span v-else>
                {{title}}
            </span>

        </div>

        <nav class="nav nav-inline explorer-header-links" v-show="selectedFile">
            <a class="nav-link" v-show="!selectedFile.isFolder" @click.stop="transferFile()"><i class="fa fa-exchange"></i> Transfer</a>
            <a class="nav-link" @click.stop="copyFile()"><i class="fa fa-copy"></i> Copy</a>
            <a class="nav-link" @click.stop="moveFile()"><i class="fa fa-arrows"></i> Move</a>
            <a class="nav-link" v-show="!selectedFile.isFolder" @click.stop="downloadFile()"><i class="fa fa-download"></i> Download</a>
            <a class="nav-link" v-show="!selectedFile.isFolder" @click.stop="shareFile()"><i class="fa fa-share"></i> Share</a>
            <a class="nav-link" @click.stop="deleteFile()"><i class="fa fa-trash"></i> Delete</a>
        </nav>
        <nav class="nav nav-inline explorer-header-links">
            <a class="nav-link" v-show="fileToBeCopied || fileToBeMoved" @click.stop="pasteFile()"><i class="fa fa-paste"></i> Paste</a>
            <a class="nav-link" v-show="canBeTransfered" @click.stop="startFileTransfer()"><i class="fa fa-angle-double-down"></i> Transfer here</a>
        </nav>


    </div>
</template>

<script>

    import fileStore from '../../stores/file';
    import sharedStore from '../../stores/shared';

    export default {

        props: [ 'file', 'account' ],

        data() {
            return {
                state: {
                    fileStore : fileStore.state,
                    sharedStore : sharedStore.state,
                },
                title: ''
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
             * fileToBeCopied
             * @return {Object}
             */
             fileToBeCopied() {
                return this.state.fileStore.fileToCopy;
            },

            /**
             * fileToBeMoved
             * @return {Object}
             */
             fileToBeMoved() {
                return this.state.fileStore.fileToMove;
            },

            /**
             * fileToBeTransfered
             * @return {Object}
             */
             fileToBeTransfered() {
                return this.state.fileStore.fileToTransfer;
            },

            /**
             * Can file be transfered at the current account
             * @return {boolean}
             */
            canBeTransfered() {
                return (this.fileToBeTransfered) && (this.currentAccount.id !== this.state.fileStore.transferFromAccount.id);
            },

            /**
             * Current Account
             * @return {Object}
             */
             currentAccount() {
                return this.account;
            },

            /**
             * Current Location
             * @return {Object}
             */
             currentLocation() {
                return this.state.fileStore.currentLocation;
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
                const title = this.currentAccount ? this.currentAccount.name : "File Explorer";
                return title;
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
             * Browse to Account Root
             */
             browseRoot() {
                //Browse Files
                fileStore.browse(this.account_id, null,
                    files => {
                        this.state.fileStore.path = [];
                    }
                    );
            },

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
                    },
                    (error) => {
                        this.state.sharedStore.errors.unshift(error);
                    }
                    );
            },

            /**
             * Delete File
             */
             deleteFile() {
                const item = this.selectedFile.isFolder ? "Folder" : "File";
                const image = this.selectedFile.thumbnailUrl ? this.selectedFile.thumbnailUrl : null;

                swal({
                    title: "Are you sure?",
                    text: "Are you sure you wanna delete this <b>" + item + "</b>?",
                    showCancelButton: true,
                    confirmButtonColor: "#d9534f",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: true,
                    html: true,
                    imageUrl: image
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
                        },
                        (error) => {
                            this.state.sharedStore.errors.unshift(error);
                        }
                        );
                });
},

            /**
             * Download File
             */
             downloadFile() {
                //Only if it's a file
                if(!this.selectedFile.isFolder) {
                    fileStore.download(this.currentAccount.id, this.selectedFile.id,
                        link => {
                            swal({
                                title: "File Ready for Download",
                                text: "The file <b>" + this.selectedFile.title + "</b> is ready for download!",
                                type: 'success',
                                confirmButtonColor: "#2b90d9",
                                confirmButtonText: "Download",
                                showLoaderOnConfirm: true,
                                allowOutsideClick: true,
                                html: true,
                            },
                            () => {
                                const file = this.selectedFile;

                                var win = window.open(link, '_blank');
                                win.focus();
                            });
                        },
                        (error) => {
                            this.state.sharedStore.errors.unshift(error);
                        }
                        );
}
},

            /**
             * Share File
             */
             shareFile() {
                //Only if it's a file
                if(!this.selectedFile.isFolder) {
                    fileStore.getShareLink(this.currentAccount.id, this.selectedFile.id,
                        link => {
                            //Dispatch the File Share Event to the Parent
                            this.$dispatch('file:share', { file: this.selectedFile, link: link });
                        },
                        (error) => {
                            this.state.sharedStore.errors.unshift(error);
                        }
                        );
                }
            },

            /**
             * Copy File
             */
             copyFile() {
                this.state.fileStore.fileToMove = false;
                this.state.fileStore.fileToTransfer = false;
                //Set the File to Copy
                this.state.fileStore.fileToCopy = this.selectedFile;
            },

            /**
             * Move File
             */
             moveFile() {
                this.state.fileStore.fileToCopy = false;
                this.state.fileStore.fileToTransfer = false;
                //Set the File to Move
                this.state.fileStore.fileToMove = this.selectedFile;
            },

            /**
             * Transfer File
             */
             transferFile() {
                this.state.fileStore.fileToMove = false;
                this.state.fileStore.fileToCopy = false;
                //Set the File to Transfer
                this.state.fileStore.fileToTransfer = this.selectedFile;
                //Set the account to Transfer From
                this.state.fileStore.transferFromAccount = this.currentAccount;
            },

            pasteFile() {
                if(this.fileToBeMoved) {
                    return this.pasteMovedFile();
                }

                if(this.fileToBeCopied) {
                    return this.pasteCopiedFile();
                }
            },

            /**
             * Paste File
             */
             pasteCopiedFile() {
                //Reset the file to be moved
                this.state.fileStore.fileToMove = false;
                //Get the File to Be Copied
                const file = this.fileToBeCopied;
                let location = this.currentLocation;

                //If a folder is selected
                if(this.selectedFile && this.selectedFile.isFolder) {
                    //Set the location as the folder
                    //To copy/paste the file inside the folder
                    location = this.selectedFile.id;
                }

                //Copy the File
                return fileStore.copy(this.currentAccount.id, file.id, location, false,
                    (error) => {
                        this.state.sharedStore.errors.unshift(error);
                    });
            },

            /**
             * Paste Moved File
             */
             pasteMovedFile() {
                //Reset the file to be copied
                this.state.fileStore.fileToCopy = false;
                //Get the File to Be Moved
                const file = this.fileToBeMoved;
                let location = this.currentLocation;

                //If a folder is selected
                if(this.selectedFile && this.selectedFile.isFolder) {
                    //Set the location as the folder
                    //To cut/paste the file inside the folder
                    location = this.selectedFile.id;
                }

                //Move the File
                return fileStore.move(this.currentAccount.id, file.id, location,
                    newFile => {
                        if(this.state.fileStore.files.length) {
                            this.state.fileStore.files.$remove(file);
                        }
                    }, (error) => {
                        this.state.sharedStore.errors.unshift(error);
                    }
                    );
            },

            /**
             * Start File Transfer
             */
             startFileTransfer() {
                //Reset the file to be moved and copied
                this.state.fileStore.fileToMove = false;
                this.state.fileStore.fileToCopy = false;

                //Get the File to Be Transfered
                const file = this.fileToBeTransfered;
                let location = this.currentLocation;

                //If a folder is selected
                if(this.selectedFile && this.selectedFile.isFolder) {
                    //Set the location as the folder
                    //To transfer the file inside the folder
                    location = this.selectedFile.id;
                }

                //Transfer the File
                return fileStore.transfer(this.currentAccount.id, file.id, location, false,
                    (error) => {
                        this.state.sharedStore.errors.unshift(error);
                    });
            },

        }
    }
</script>