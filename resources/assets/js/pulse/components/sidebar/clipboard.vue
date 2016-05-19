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
                                    <!-- <div id="scheduled_at"></div> -->
                                    <input type="text" id="scheduled_at" placeholder="Schedule At" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-item-actions clearfix" v-show="transferToAccount">
                            <a @click.stop="startFileTransfer()">Start</a>
                            <a @click.stop="scheduleTransfer()" v-show="!state.fileStore.scheduling" class="pull-right">Schedule</a>
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
    let picker = null;

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

            scheduleTransfer() {
                this.state.fileStore.scheduling = true;
                const min = moment().add(1, 'minutes');
                const max = moment().add(30, 'days');
                const element = document.getElementById('scheduled_at');
                picker = rome(element, { min: min, max: max, inputFormat: "Do MMM, YYYY, hh:mm A", timeInterval: 60 });
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

                if(this.state.fileStore.scheduling) {
                    this.startScheduledTransfer();
                } else {
                    //Scheduled Transfer the File
                    return fileStore.transfer(this.transferToAccount.id, file.id, location, false,
                        (error) => {
                            this.state.sharedStore.errors.unshift(error);
                        }
                        );
                }
            },

            startScheduledTransfer() {
                //Get the File to Be Transfered
                const file = this.fileToBeTransfered;
                const location = this.transferToLocation ? this.transferToLocation : null ;
                const scheduled_at = picker.getDateString("YYYY-MM-DD HH:mm:ss");

                fileStore.scheduleTransfer(
                    this.transferToAccount.id, file.id, scheduled_at, location, () => {

                        swal({
                            title: "Transfer Scheduled!",
                            type: 'success',
                            allowOutsideClick: true,
                            timer: 5000
                        });

                    });
            }
        }

    }
</script>