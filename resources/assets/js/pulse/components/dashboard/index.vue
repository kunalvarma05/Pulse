<template>

    <div id="dashboard">
        <navbar></navbar>
        <sidemenu></sidemenu>
        <connect-account></connect-account>
        <div class="dashboard-content">
            <router-view></router-view>
        </div>
    </div>

</template>

<script>

    //User Store
    import userStore from '../../stores/user.js';
    //Shared Store
    import sharedStore from '../../stores/shared.js';

    //Navbar
    import navbar from '../navbar/index.vue';
    //Sidemenu
    import sidemenu from '../sidemenu/index.vue';

    import connectAccount from '../modals/connect-account.vue';

    export default {

        components: { navbar, sidemenu, connectAccount },

        data() {
            return {
                state: {
                    userStore: userStore.state
                },
            };
        },

        ready() {
            //Initialize
            this.init();
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