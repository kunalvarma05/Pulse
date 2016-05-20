<template>
    <div id="admin">
        <navbar></navbar>
        <div class="admin-content">
            <div class="container">
                <router-view></router-view>
            </div>
        </div>
    </div>

</template>

<script>

    //User Store
    import userStore from '../../stores/user.js';
    //Shared Store
    import sharedStore from '../../stores/shared.js';

    //Navbar
    import navbar from './navbar.vue';


    export default {

        components: { navbar },

        data() {
            return {
                state: {
                    userStore: userStore.state
                },
            };
        },

        ready() {
        },

        route: {
            data() {
                if(!this.currentUser.is_admin) {
                    this.$route.router.go({ name: 'index' });
                }
            }
        },

        computed: {

            /**
             * Current User
             * @return {Object}
             */
             currentUser() {
                return this.state.userStore.current;
            },

        },

        methods: {

            /**
             * Initialize the dashboard
             */
             init() {

                //Initialize
                sharedStore.init(
                    response => {
                        //The app is ready
                        //Let the parent know
                        this.$dispatch("pulse:ready");
                        //Let the children know
                        this.$broadcast("pulse:ready");
                    },
                    () => {
                        //The User ain't logged in,
                        //log'em out.
                        this.$dispatch("user:loggedout");
                    }
                    );
            }
        },

        events: {

            "pulse:ready"() {
                //Let the event propogate
                return true;
            }

        },
    };
</script>