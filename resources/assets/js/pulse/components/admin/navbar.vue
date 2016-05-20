<template>
    <nav class="navbar navbar-fixed-top navbar-light navbar-main is-full">
        <a href="#">
            <img src="/images/logo-dark.png" alt="pulse logo" style="width: 38px; height: 38px; float: left;">
        </a>
        <button class="navbar-toggler hidden-sm-up pull-right" type="button" data-toggle="collapse" data-target="#navbarCollapsibleContent">
            <i class="fa fa-user"></i>
        </button>
        <div class="collapse navbar-toggleable-xs" id="navbarCollapsibleContent">
            <ul class="nav navbar-nav navbar-info-nav">

                <li class="nav-item">
                    <a class="nav-link" v-link="{ name: 'admin-stats' }">Statistics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-link="{ name: 'manage-users' }">Users</a>
                </li>

                <user-profile :user.sync='currentUser'></user-profile>


            </ul>
        </div>
    </nav>
</template>

<script>

    //User Store
    import userStore from '../../stores/user.js';

    import userProfile from '../navbar/user-profile.vue';

    export default {

        components: { userProfile },

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