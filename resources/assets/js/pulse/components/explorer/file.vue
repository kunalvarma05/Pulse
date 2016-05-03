<template>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div @click.stop="selectFile(account_id, file)" @dblclick.stop="browseFolder(account_id, file)" data-toggle-tooltip="explorer" :title="file.title" class="card explorer-item" :class=" { 'selected': file === selectedFile } ">

            <div class="explorer-item-thumbnail card-img-top" :style="{ backgroundImage: file.thumbnailUrl ? 'url(' + file.thumbnailUrl + ')' : '' }">
                <i class="fa explorer-item-icon" :class="file.icon" :style="{ opacity: file.thumbnailUrl ? 0 : 1 }"></i>
            </div>

            <div class="card-block explorer-item-body">
                <div class="card-title explorer-item-title">
                    {{file.title}}
                </div>
            </div>

        </div>
    </div>

</template>

<script>

    import fileStore from '../../stores/file';

    export default {

        props: [ 'file', 'index' ],

        data() {
            return {
                state: {
                    fileStore : fileStore.state,
                },
            };
        },

        computed: {

            /**
             * Account ID
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
             * Current Location
             * @return {Object}
             */
             currentLocation() {
                return this.state.fileStore.currentLocation;
            },

        },

        methods: {

            /**
             * Select File
             * @param  {Object} account      Current Account
             * @param  {Object} selectedFile Selected File
             */
             selectFile(account, selectedFile) {
                this.state.fileStore.selected = selectedFile;
            },

            /**
             * Browse Folder
             * @param  {Object} account      Current Account
             * @param  {Object} selectedFile Selected File
             * @return {Promise}
             */
             browseFolder(account, selectedFile) {
                //If the selected file
                //is a folder, duh.
                if(selectedFile.isFolder) {
                    //Browse the Folder
                    return fileStore.browse(account, selectedFile.id,
                        (files) => {
                            //Reset the selected file
                            this.state.fileStore.selected = false;
                            //Add folder to the current explorer path
                            fileStore.state.path.push(selectedFile);
                        }
                        );
                }
            },

            /**
             * Deselect the File
             */
             deSelectFile() {
                fileStore.selected = false;
            }

        },

        events: {

            /**
             * DeSelect File Event
             */
            'explorer:deSelectFile'() {
                //DeSelect the selected file
                this.deSelectFile();
            }
        }
    }
</script>