<template>
    <div class="explorer has-sidebar" id="explorer">
        <div class="explorer-header clearfix">
            <div class="explorer-header-title">{{ title }}</div>

            <nav class="nav nav-inline explorer-header-links">
                <a class="nav-link active" href="#"><i class="fa fa-info-circle"></i> Info</a>
                <a class="nav-link" href="#"><i class="fa fa-copy"></i> Copy</a>
                <a class="nav-link" href="#"><i class="fa fa-arrows"></i> Move</a>
                <a class="nav-link" href="#"><i class="fa fa-download"></i> Download</a>
                <a class="nav-link" href="#"><i class="fa fa-share"></i> Share</a>
                <a class="nav-link" href="#"><i class="fa fa-trash"></i> Delete</a>
            </nav>
        </div>

        <div class="explorer-content" data-scrollbar='true'>
            <div class="container-fluid">
                <div class="row explorer-items">


                    <div v-for="file in state.fileStore.files" class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                        <div  @dblclick="browseFolder(state.accountStore.current, file)" :style="'animation-delay: 0.' + $index + 's;'" class="card explorer-item" data-toggle-tooltip="tooltip" :title="file.title">
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

                    <h4 class="text-center" v-show='!state.fileStore.files' align="center">No files found!</h4>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import config from '../../config';
    import fileStore from '../../stores/file';
    import userStore from '../../stores/user';
    import accountStore from '../../stores/account';

    export default {
        data() {
            return {
                state: {
                    fileStore : fileStore.state,
                    accountStore : accountStore.state
                },
                title: "File Explorer"
            };
        },

        methods: {
            viewInfo: (file) => {
                //alert(file);
            },

            browseFolder: (account, file) => {
                console.log(account, file);
                if(file.isFolder) {
                    fileStore.browse(account, file.id);
                }
            }
        }
    }
</script>