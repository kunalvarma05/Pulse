<template>
    <div class="explorer" id="explorer">

        <explorer-header></explorer-header>

        <div class="explorer-content" data-scrollbar='true'>
            <div class="container-fluid">
                <div class="row explorer-items">

                    <explorer-file v-for="fileItem in state.fileStore.files" :file='fileItem' :index='$index'></explorer-file>

                    <h4 class="text-center" v-show='!state.fileStore.files' align="center">No files found!</h4>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import fileStore from '../../stores/file';
    import accountStore from '../../stores/account';

    import explorerHeader from './header.vue';
    import explorerFile from './file.vue';


    export default {

        components: { explorerHeader, explorerFile },

        data() {
            return {
                state: {
                    fileStore : fileStore.state,
                },
                title: "File Explorer"
            };
        },

        computed: {
            account_id() {
                return this.$route.params.account_id;
            }
        },

        route: {
            data() {
                fileStore.init(false, []);
                //Get Account
                accountStore.getInfo(this.account_id,
                    (account) => {
                        accountStore.current = account;
                    }
                );

                //Browse Files
                fileStore.browse(this.account_id);
            }
        },

        methods: {
        }
    }
</script>