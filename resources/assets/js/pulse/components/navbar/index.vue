<template>
    <nav class="navbar navbar-fixed-top navbar-light navbar-main">

        <!-- <search></search> -->

        <button class="navbar-toggler hidden-sm-up pull-right" type="button" data-toggle="collapse" data-target="#navbarCollapsibleContent">
            <i class="fa fa-user"></i>
        </button>
        <div class="collapse navbar-toggleable-xs" id="navbarCollapsibleContent">
            <ul class="nav navbar-nav navbar-info-nav">

                <user-profile :user.sync='currentUser'></user-profile>

            </ul>
        </div>
    </nav>
</template>

<script>

    //User Store
    import userStore from '../../stores/user.js';

    import search from './search.vue';
    import userProfile from './user-profile.vue';

    export default {

        components: { search, userProfile },

        data() {
            return {
                state: {
                    userStore: userStore.state
                },
            };
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

    }
</script>