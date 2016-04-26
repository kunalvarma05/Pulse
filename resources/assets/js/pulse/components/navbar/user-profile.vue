<template>
    <img v-bind:src="currentUser.picture" class="navbar-account-image">
    <li class="nav-item dropdown account-dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown">
            {{currentUser.name}} <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item">Profile</a>
            <a class="dropdown-item">Settings</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" @click="logout()">Log out</a>
        </div>
    </li>
</template>

<script>

    export default {

        props: [ 'user' ],

        computed: {

            /**
             * Current User
             * @return {Object}
             */
             currentUser() {
                return this.user;
            },

        },

        methods: {

            /**
             * Log out
             */
            logout() {
                //Logout
                this.$dispatch('user:loggedout');
            }

        }
    }
</script>