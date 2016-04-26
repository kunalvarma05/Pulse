<template>
    <div class="explorer-header clearfix">
        <div class="explorer-header-title">
            <div v-if="path.length" :class="'explorer-header-breadcrumb'">
                <a v-for="path in state.fileStore.path" @click="browseBack(account_id, path, $index)"> {{path.title}} </a>
            </div>
            <span v-else>
                {{title}}
            </span>
        </div>

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
                title: '',
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
                const title = accountStore.current ? accountStore.current.name : "File Explorer";
                return (this.selectedFile) ? this.selectedFile.title : title;
            },

            account_id() {
                return this.$route.params.account_id;
            }
        },

        methods: {
            browseBack: (account, selectedFile, index) => {
                fileStore.state.selected = false;
                let breadcrumbs = [];
                fileStore.browse(account, selectedFile.id,
                    (files) => {
                        for (var i = 0; i <= index; i++) {
                            let crumb = fileStore.state.path[i];
                            breadcrumbs.push(crumb);
                        };
                        fileStore.path = breadcrumbs;
                    }
                    );
            },
        }
    }
</script>