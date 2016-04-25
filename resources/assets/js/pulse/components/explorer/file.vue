<template>
    <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
        <div @click="viewInfo(state.accountStore.current, file)" v-on-clickaway="away" @dblclick="browseFolder(state.accountStore.current, file)" :style="'animation-delay: 0.' + $index + 's;'" class="card explorer-item" data-toggle-tooltip="tooltip" :title="file.title">
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
    import accountStore from '../../stores/account';

    import { directive as onClickaway } from 'vue-clickaway';

    export default {

        //mixins: [ clickaway ],
        props: [ 'file' ],
        directives: {
            onClickaway: onClickaway,
        },
        components: { },

        data() {
            return {
                state: {
                    fileStore : fileStore.state,
                    accountStore : accountStore.state
                },
            };
        },

        methods: {
            viewInfo: (account, selectedFile) => {
                fileStore.state.selected = selectedFile;
            },

            browseFolder: (account, selectedFile) => {
                if(selectedFile.isFolder) {
                    fileStore.browse(account, selectedFile.id);
                }
            },

            away: function() {
                fileStore.state.selected = false;
            },
        }
    }
</script>