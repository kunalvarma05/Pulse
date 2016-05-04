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
                                <span class="item-detail-title">Title</span>
                                <span class="item-detail-value">{{ fileToBeTransfered.title }}</span>
                            </div>
                            <div class="item-detail" v-show='!fileToBeTransfered.isFolder'>
                                <span class="item-detail-title">Type</span>
                                <span class="item-detail-value">{{ fileToBeTransfered.mimeType }}</span>
                            </div>
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

        },

        methods: {
            clearClipboard() {
                this.state.fileStore.fileToMove = false;
                this.state.fileStore.fileToCopy = false;
                this.state.fileStore.fileToTransfer = false;
            }
        }

    }
</script>