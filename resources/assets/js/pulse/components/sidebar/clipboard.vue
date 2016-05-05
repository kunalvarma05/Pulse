<template>
    <div v-show="fileToBeCopied || fileToBeMoved || fileToBeTransfered">
        <div class="sidebar-header animated slideInRight">
            Clipboard
            <a @click.stop="clearClipboard()">Clear</a>
        </div>
        <div v-show='fileToBeCopied'>
            <div class="sidebar-body">
                <div class="sidebar-items">
                    <div class="sidebar-item">
                        <div class="sidebar-item-body has-details">
                            <div class="item-detail">
                                <span class="item-detail-title">Title</span>
                                <span class="item-detail-value">{{ fileToBeCopied.title }}</span>
                            </div>
                            <div class="item-detail" v-show='!fileToBeCopied.isFolder'>
                                <span class="item-detail-title">Type</span>
                                <span class="item-detail-value">{{ fileToBeCopied.mimeType }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-show='fileToBeMoved'>
            <div class="sidebar-body">
                <div class="sidebar-items">
                    <div class="sidebar-item">
                        <div class="sidebar-item-body has-details">
                            <div class="item-detail">
                                <span class="item-detail-title">Title</span>
                                <span class="item-detail-value">{{ fileToBeMoved.title }}</span>
                            </div>
                            <div class="item-detail" v-show='!fileToBeMoved.isFolder'>
                                <span class="item-detail-title">Type</span>
                                <span class="item-detail-value">{{ fileToBeMoved.mimeType }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-show='fileToBeTransfered'>
            <div class="sidebar-body">
                <div class="sidebar-items">
                    <div class="sidebar-item">
                        <div class="sidebar-item-body has-details">
                            <div class="item-detail">
                                <span class="item-detail-title">File</span>
                                <span class="item-detail-value">{{ fileToBeTransfered.title }}</span>
                            </div>
                            <div class="item-detail">
                                <span class="item-detail-title">Transfer From</span>
                                <span class="item-detail-value">{{ transferFromAccount.name }}</span>
                            </div>
                            <div class="item-detail" v-show="transferToAccount">
                                <span class="item-detail-title">Transfer To</span>
                                <span class="item-detail-value">{{ transferToAccount.name }}</span>
                            </div>
                            <div class="item-detail" v-show="transferToLocation">
                                <span class="item-detail-title">Location</span>
                                <span class="item-detail-value">{{ transferToLocation }}</span>
                            </div>
                            <div class="item-detail" v-show="state.fileStore.scheduling">
                                <span class="item-detail-title">Schedule At</span>
                                <div class="form-group">
                                    <input type="time" placeholder="Schedule At" class="form-control input-sm" v-model="state.fileStore.scheduled_at">
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-item-actions clearfix">
                            <a @click.stop="startFileTransfer()" v-show="transferToAccount && transferToLocation">Start</a>
                            <a @click.stop="scheduleFileTransfer()" v-show="!state.fileStore.scheduling" class="pull-right">Schedule</a>
                            <a @click.stop="state.fileStore.scheduling=false" v-show="state.fileStore.scheduling" class="cancel">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    import fileStore from '../../stores/file';

    export default {

        props: [ 'account' ],

        data() {
            return {
                state: {
                    fileStore: fileStore.state
                }
            };
        },

        computed: {

            /**
             * Current Account
             * @return {Object}
             */
             currentAccount() {
                return this.account;
            },

            /**
             * fileToBeCopied
             * @return {Object}
             */
             fileToBeCopied() {
                return this.state.fileStore.fileToCopy;
            },

            /**
             * fileToBeMoved
             * @return {Object}
             */
             fileToBeMoved() {
                return this.state.fileStore.fileToMove;
            },

            /**
             * fileToBeTransfered
             * @return {Object}
             */
             fileToBeTransfered() {
                return this.state.fileStore.fileToTransfer;
            },

            /**
             * transferToLocation
             * @return {Object}
             */
             transferToLocation() {
                return this.state.fileStore.transferToLocation;
            },

            /**
             * transferFromAccount
             * @return {Object}
             */
             transferFromAccount() {
                return this.state.fileStore.transferFromAccount;
            },

            /**
             * transferToAccount
             * @return {Object}
             */
             transferToAccount() {
                return this.state.fileStore.transferToAccount;
            },

        },

        methods: {
            clearClipboard() {
                this.state.fileStore.fileToMove = false;
                this.state.fileStore.fileToCopy = false;
                this.state.fileStore.fileToTransfer = false;
                this.state.fileStore.transferFromAccount = false;
                this.state.fileStore.transferToLocation = false;
            },

            /**
             * Start File Transfer
             */
             startFileTransfer() {
                //Reset the file to be moved and copied
                this.state.fileStore.fileToMove = false;
                this.state.fileStore.fileToCopy = false;

                //Get the File to Be Transfered
                const file = this.fileToBeTransfered;
                const location = this.transferToLocation;
                const scheduled_at = this.state.fileStore.scheduled_at;

                if(this.state.fileStore.scheduling) {
                    this.startScheduledTransfer();
                } else {
                    //Scheduled Transfer the File
                    return fileStore.scheduledTransfer(this.transferToAccount.id, file.id, location, false,
                        (error) => {
                            this.state.sharedStore.errors.unshift(error);
                        }
                        );
                }
            },

            /**
             * Schedule File Transfer
             */
             scheduleFileTransfer() {
                this.state.fileStore.scheduling = true;
            },

            startScheduledTransfer() {
                swal({
                    title: "Transfer Scheduled!",
                    type: 'success',
                    allowOutsideClick: true,
                    text: 'The ' + '<b> ' + this.fileToBeTransfered.title + '</b> will be transfered to <b>' + this.transferToAccount.name + '</b> from <b>' + this.transferFromAccount.name + '</b> on <b>' + this.state.fileStore.scheduled_at + '</b>',
                    timer: 5000,
                    html: true
                });
            }
        }

    }
</script>