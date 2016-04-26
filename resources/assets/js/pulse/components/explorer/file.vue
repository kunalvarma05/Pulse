<template>
    <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
        <div @click.stop="selectFile(account_id, file)" @dblclick.stop="browseFolder(account_id, file)" v-on-clickaway="deSelectFile()" class="card explorer-item" data-toggle-tooltip="tooltip" :title="file.title">
            <div class="explorer-item-thumbnail card-img-top">
                <i :class="'fa explorer-item-icon ' + file.icon"></i>
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

    import { mixin as clickaway } from 'vue-clickaway';

    export default {

        mixins: [ clickaway ],

        props: [ 'file', 'index' ],

        components: { },

        data() {
            return {
                state: {
                    fileStore : fileStore.state,
                },
            };
        },

        computed: {
            account_id() {
                return this.$route.params.account_id;
            },
        },

        methods: {

            selectFile: (account, selectedFile) => {
                fileStore.state.selected = selectedFile;
            },

            browseFolder: (account, selectedFile) => {
                if(selectedFile.isFolder) {
                    fileStore.state.selected = false;
                    fileStore.browse(account, selectedFile.id,
                        (files) => {
                            fileStore.state.path.push(selectedFile);
                        }
                    );
                }
            },

            deSelectFile() {
                fileStore.selected = false;
            }
        }
    }
</script>