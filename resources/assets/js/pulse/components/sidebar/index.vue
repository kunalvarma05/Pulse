<template>
<div class="sidebar" data-scrollbar='true' @click.stop="">
        <file-info :file='file'></file-info>
    </div>
</template>

<script>
    import fileStore from '../../stores/file';
    import accountStore from '../../stores/account';

    import fileInfo from './fileinfo.vue';

    export default {
        props: [ 'file' ],

        components: { fileInfo },

        data() {
            return {

            };
        }
    }
</script>