<template>
    <div class="modal fade" id="share-file-modal" tabindex="-1" role="dialog" aria-labelledby="shareFileModalLabel" aria-hidden="true" @click.stop="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="text-overflow-ellipsis modal-title" id="shareFileModalLabel">Share: {{ selectedFile.title }}</h4>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="form-group">
                            <label>Link to File</label>
                            <input type='text' onfocus='this.select();' onmouseup='return false;' :value="shareLink" class='form-control'>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        data() {
            return {
                shareLink: '',
                selectedFile: false
            };
        },

        events: {

            /**
             * File Share Event
             */
            "file:share"(data) {
                this.shareLink = data.link;
                this.selectedFile = data.file;
                //Show the Modal
                const modal = global.jQuery('#share-file-modal').modal({ show: true });
            }
        }

    }
</script>