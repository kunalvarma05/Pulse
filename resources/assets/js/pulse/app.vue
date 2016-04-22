<template>
    <div id="app" tabindex="0" v-show="authenticated">
        <navbar></navbar>
        <sidemenu></sidemenu>
        <connect-account-modal></connect-account-modal>
    </div>

    <div class="login-wrapper" v-else>
        <login-form></login-form>
    </div>
</template>

<script>
    import Vue from 'vue';
    import $ from 'jquery';

    import navbar from './components/navbar/index.vue';
    import sidemenu from './components/sidemenu/index.vue';
    import loginForm from './components/auth/login-form.vue';
    import connectAccountModal from './components/modals/connect-account.vue';

    import sharedStore from './stores/shared';
    import userStore from './stores/user';
    import ls from './services/ls';


    export default {
        components: { navbar, sidemenu, loginForm, connectAccountModal },

        replace: false,

        data() {
            return {
                authenticated: false,
            };
        },

        ready() {
            // The app has just been initialized, check if we can get the user data with an already existing token
            const token = ls.get('token');

            if (token) {
                this.authenticated = true;
                this.init();
            }

        },

        methods: {

            init() {

                // Make the most important HTTP request to get all necessary data from the server.
                // Afterwards, init all mandatory stores and services.
                sharedStore.init(
                    () => {
                        // Let all other components know we're ready.
                        this.$broadcast('pulse:ready');
                    },

                    () => this.authenticated = false
                );
            },

            /**
             * Log the current user out and reset the application state.
             */
             logout() {
                userStore.logout(() => {
                    ls.remove('token');
                    this.authenticated = false;
                    this.$broadcast('pulse:teardown');
                });
            },
        },

        events: {
            /**
             * When the user logs in, set the whole app to be "authenticated" and initialize it.
             */
             'user:loggedin': function () {
                this.authenticated = true;
                this.init();
            }
        },
    };
</script>