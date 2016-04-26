<template>
    <div id="dashboard">
        <navbar></navbar>
        <sidemenu></sidemenu>
        <router-view></router-view>
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

    export default {
        components: { navbar, sidemenu },

        data() {
            return {
            };
        },

        ready() {
            //Initialize
            this.init();
        },

        route: {
            data() {

            }
        },

        methods: {

            /**
             * Initialize the dashboard
             */
             init() {
                sharedStore.init(
                    response => {
                        //The app is ready
                        //Let the parent know
                        this.$dispatch("pulse:ready");
                        //Let the children know
                        this.$broadcast("pulse:ready");
                    },
                    () => {
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