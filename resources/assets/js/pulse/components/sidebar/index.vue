<template>

    <div class="sidebar" data-scrollbar='true' @click.stop="">
        <file-info :file.sync='file'></file-info>
    </div>

</template>

<script>

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