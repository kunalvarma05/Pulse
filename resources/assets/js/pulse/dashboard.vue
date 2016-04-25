<template>
    <div id="dashboard">
        <h1>Dashboard</h1>
    </div>

</template>

<script>
    //User Store
    import userStore from './stores/user.js';
    //Shared Store
    import sharedStore from './stores/shared.js';

    export default {
        components: {},

        data() {
            return {
            };
        },

        ready() {
        },

        route: {
            data() {
                //Initialize
                this.init();
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