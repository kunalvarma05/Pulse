<template>
    <div class="explorer-header clearfix">
        <div class="explorer-header-title">{{ title }}</div>

        <nav class="nav nav-inline explorer-header-links" v-show="state.fileStore.selected">
            <a class="nav-link active"><i class="fa fa-info-circle"></i> Info</a>
            <a class="nav-link"><i class="fa fa-copy"></i> Copy</a>
            <a class="nav-link"><i class="fa fa-arrows"></i> Move</a>
            <a class="nav-link"><i class="fa fa-download"></i> Download</a>
            <a class="nav-link"><i class="fa fa-share"></i> Share</a>
            <a class="nav-link"><i class="fa fa-trash"></i> Delete</a>
        </nav>
    </div>
</template>

<script>
    import fileStore from '../../stores/file';
    import userStore from '../../stores/user';
    import accountStore from '../../stores/account';

    export default {
        data() {
            return {
                state: {
                    fileStore : fileStore.state,
                    accountStore : accountStore.state,
                },
                title: 'File Explorer',
                selectedFile: ''
            };
        },

        computed: {
            selectedFile() {
                return this.state.fileStore.selected;
            },

            title() {
                let selectedFile = this.selectedFile;
                return selectedFile ? selectedFile.title : "File Explorer";
            },
        },

        methods: {
        }
    }
</script>