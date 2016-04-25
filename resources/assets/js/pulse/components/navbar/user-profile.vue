<template>
    <img v-bind:src="state.current.picture" class="navbar-account-image">
    <li class="nav-item dropdown account-dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown">
            {{state.current.name}} <span class="caret"></span>
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
    import userStore from '../../stores/user';

    export default {
        data() {
            return {
                state: userStore.state
            };
        },

        methods: {
            logout() {
                //Logout
                this.$dispatch('user:logout');
            }
        }
    }
</script>