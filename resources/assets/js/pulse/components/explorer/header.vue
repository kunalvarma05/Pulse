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

            path() {
                return this.state.fileStore.path;
            },

            title() {
                //Selected file
                let selectedFile = this.selectedFile;
                //Current Explorer Path
                let path = this.path;
                //Breadcrumb
                let breadcrumb = (selectedFile) ? selectedFile.title : "File Explorer";
                //If the current path has directories
                if(path.length) {
                    //Make breadcrumb
                    breadcrumb = path.join('/');

                    //If a file is selected
                    if(selectedFile && !selectedFile.isFolder){
                        //Add it to the breadcrumb
                        breadcrumb += "/" + selectedFile.title;
                    }

                    return breadcrumb;
                }

                return breadcrumb;
            },
        },

        methods: {
        }
    }
</script>