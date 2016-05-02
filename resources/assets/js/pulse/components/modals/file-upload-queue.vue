<template>
    <div class="file-upload-queue-box" :class="{ 'hidden': !state.visible }">
        <div class="queue-box-header">
            <span class="queue-box-title">Uploads</span>
            <a class="close-button fa fa-remove" @click="close()"></a>
        </div>
        <div class="file-upload-queue">
        </div>
    </div>
</template>

<script>

    import fileStore from '../../stores/file';

    export default {

        props: ['account'],

        data() {
            return {
                state: {
                    fileStore: fileStore.state,
                    visible: true
                }
            };
        },

        computed: {

        },

        methods: {
            close() {
                this.state.visible = false;
            }
        },

        events: {

            "file:queued"(data) {
                this.state.visible = true;
            }

        }

    }
</script>