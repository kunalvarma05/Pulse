<template>
    <img v-bind:src="state.current.picture" class="navbar-account-image">
    <li class="nav-item dropdown account-dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
            {{state.current.name}} <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="#">Profile</a>
            <a class="dropdown-item" href="#">Settings</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" @click="logout()">Log out</a>
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
        }
    }
}
</script>