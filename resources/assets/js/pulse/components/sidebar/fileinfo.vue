<template>
    <div class="sidebar-header animated slideInRight">
        {{ file.title }}
    </div>

    <div class="sidebar-body">
        <div class="sidebar-items">
            <div class="sidebar-item">
                <div class="sidebar-item-body has-details">
                    <div class="item-detail" v-show='!file.isFolder'>
                        <span class="item-detail-title">Type</span>
                        <span class="item-detail-value">{{ file.mimeType }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Size</span>
                        <span class="item-detail-value">{{ file.size }}</span>
                    </div>
                    <div class="item-detail" v-show='file.owners'>
                        <span class="item-detail-title">Owner</span>
                        <span class="item-detail-value">{{ file.owners }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Path</span>
                        <span class="item-detail-value">{{ file.path }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Modified</span>
                        <span class="item-detail-value">{{ file.modified }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [ 'file' ],

        data() {
            return {
            };
        },
    }
</script>