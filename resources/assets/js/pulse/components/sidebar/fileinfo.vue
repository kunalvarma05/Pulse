<template>
    <div class="sidebar-header animated slideInRight">
        {{ selectedFile.title }}
    </div>

    <div class="sidebar-body">
        <div class="sidebar-items">
            <div class="sidebar-item" style="animation-delay: 0.5s;">
                <div class="sidebar-item-body has-details">
                    <div class="item-detail" v-show='!selectedFile.isFolder'>
                        <span class="item-detail-title">Type</span>
                        <span class="item-detail-value">{{ selectedFile.mimeType }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Size</span>
                        <span class="item-detail-value">{{ selectedFile.size }}</span>
                    </div>
                    <div class="item-detail" v-show='selectedFile.owners'>
                        <span class="item-detail-title">Owner</span>
                        <span class="item-detail-value">{{ selectedFile.owners }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Path</span>
                        <span class="item-detail-value">{{ selectedFile.path }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Modified</span>
                        <span class="item-detail-value">{{ selectedFile.modified }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import fileStore from '../../stores/file';
    import accountStore from '../../stores/account';

    export default {
        data() {
            return {
                state: {
                    fileStore: fileStore.state,
                    accountStore: accountStore.state
                }
            };
        },

        computed: {
            selectedFile() {
                return this.state.fileStore.selected;
            },
        }
    }
</script>