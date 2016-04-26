<template>
<div class="sidebar" data-scrollbar='true' @click.stop="">
        <file-info></file-info>
    </div>
</template>

<script>
    import fileStore from '../../stores/file';
    import accountStore from '../../stores/account';

    import fileInfo from './fileinfo.vue';

    export default {

        components: { fileInfo },

        data() {
            return {

            };
        }
    }
</script>