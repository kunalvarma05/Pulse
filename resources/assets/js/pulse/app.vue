<template>
    <router-view></router-view>
    <error-notifier></error-notifier>
</template>

<script>
    import ls from './services/ls';
    //Shared Store
    import sharedStore from './stores/shared.js';
    //User Store
    import userStore from './stores/user.js';

    //Errors
    import errorNotifier from './components/errors/index.vue';


    export default {
        components: { errorNotifier },
        replace: false,
        data() {
            return {
                authenticated: false,
            };
        },

        ready() {
            //Initialize
            this.init();
        },

        methods: {

            init() {
                const token = ls.get('token');
                //If a token is present
                //the user seems authenticated
                if (token) {
                    this.authenticated = true;
                }
            },

            logout() {
                userStore.logout(
                    () => {
                        ls.remove('token');
                        this.authenticated = false;
                        return this.$route.router.go({ name: 'login' });
                    }
                    );
            }
        },

        events: {
            "user:loggedin"() {
                //User logged in
                this.authenticated = true;
                //Redirect to the dashboard
                return this.$route.router.go({ name: 'dashboard' });
            },

            "user:loggedout"() {
                //Log the user out
                this.logout();
            },

            "pulse:ready"() {
            }
        },
    };
</script>