<template>
    <!-- The App Instance, where the magic happens! -->
    <div id="app">
        <!-- Navbar -->
        <navbar></navbar>
        <!-- Sidemenu -->
        <sidemenu></sidemenu>

        <!-- Router View -->
        <router-view></router-view>
    </div>

</template>

<script>
    import navbar from './components/navbar/index.vue';
    import sidemenu from './components/sidemenu/index.vue';

    import sharedStore from './stores/shared';
    import userStore from './stores/user';
    import ls from './services/ls';


    export default {
        components: { navbar, sidemenu },

        replace: false,

        data() {
            return {
            };
        },

        route: {
            data() {

            }
        },

        ready() {
        },

        methods: {
        },

        events: {
        },
    };
</script>