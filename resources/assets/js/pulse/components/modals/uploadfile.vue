<template>
    <div class="modal fade" id="upload-file-modal" tabindex="-1" role="dialog" aria-labelledby="uploadFileModalLabel" aria-hidden="true" @click.stop="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="text-overflow-ellipsis modal-title" id="uploadFileModalLabel">Upload File to: {{currentAccount.name}}</h4>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div id="file-upload-area" class="file-upload-area">
                            <h4 class="text-lg-center" id="file-upload-title">Click to upload files...</h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div id="file-upload-preview-template" class="hidden">
            <div class="file-preview">
                <div class="file-info clearfix">
                    <span class="pull-left fa fa-file file-icon"></span>
                    <div class="pull-left file-name text-overflow-ellipsis" data-dz-name></div>
                    <div class="pull-right file-size" data-dz-size></div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" data-dz-uploadprogress></div>
                </div>
                <span class="label label-danger" data-dz-errormessage></span>
            </div>
        </div>
    </div>

    <div v-show="queueHasFiles">
        <file-upload-queue :account.sync="currentAccount"></file-upload-queue>
    </div>
</template>

<script>

    import ls from '../../services/ls';
    import fileUploadQueue from './file-upload-queue.vue';

    export default {

        props: [ 'account' ],

        components: { fileUploadQueue },

        data() {
            return {
                state: {
                    queue: []
                },
                fileUploadModal: false,
                dropzone: false,
            };
        },

        ready() {
            this.fileUploadModal = jQuery('#upload-file-modal').modal({ show: false, backdrop: 'static' });

            const url = "/api/accounts/" + this.currentAccount.id + "/manager/upload";
            const token = ls.get('token');

            // Dropzone
            Dropzone.autoDiscover = false;

            //Upload Area
            const fileUploadArea = jQuery("#file-upload-area");
            const originalTitle = fileUploadArea.text();
            const fileQueue = jQuery(".file-upload-queue");


            this.dropzone = new Dropzone("#file-upload-area", {
                url: url,
                autoProcessQueue: true,
                createImageThumbnails: false,
                parallelUploads: 5,
                clickable: true,
                previewTemplate: document.getElementById('file-upload-preview-template').innerHTML,
                previewsContainer: ".file-upload-queue",
                headers: {
                    "Authorization": "Bearer " + token
                }
            });

            this.dropzone.on("success", (file, data) => {
                let filePreview = jQuery(file.previewElement);
                let progress = filePreview.find(".progress-bar");
                progress.addClass("progress-bar-success");
                progress.removeClass("active");
            });

            this.dropzone.on("addedfile", file => {
                this.state.queue.push(file);
            });

            jQuery(document).on({
                dragover: event => {
                    this.fileUploadModal.modal('show');
                    fileUploadArea.find("#file-upload-title").text("Drag your files here...");
                },
                dragleave: event => {
                    fileUploadArea.find("#file-upload-title").text(originalTitle);
                }
            });

            fileUploadArea.on({
                dragover: event => {
                    fileUploadArea.addClass('active');
                    fileUploadArea.find("#file-upload-title").text("Drop files...");
                },
                dragleave: event => {
                    fileUploadArea.removeClass('active');
                    fileUploadArea.find("#file-upload-title").text(originalTitle);
                },
                drop: event => {
                    fileUploadArea.removeClass('active');
                    fileUploadArea.find("#file-upload-title").text(originalTitle);
                }
            });

        },

        computed: {

            /**
             * Current Account
             * @return {Object}
             */
             currentAccount() {
                return this.account;
            },

            queueHasFiles() {
                return this.state.queue.length;
            }

        },

        methods: {

        },

        events: {

            "file:upload"(data) {
                this.fileUploadModal.modal('show');
            }

        }

    }
</script>