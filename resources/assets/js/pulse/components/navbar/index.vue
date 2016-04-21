<template>
    <nav class="navbar navbar-fixed-top navbar-light navbar-main">

        <search></search>

        <button class="navbar-toggler hidden-sm-up pull-right" type="button" data-toggle="collapse" data-target="#navbarCollapsibleContent">
            <i class="fa fa-user"></i>
        </button>
        <div class="collapse navbar-toggleable-xs" id="navbarCollapsibleContent">
            <ul class="nav navbar-nav navbar-info-nav">

                <notification-menu></notification-menu>

                <user-profile></user-profile>
            </ul>
        </div>
    </nav>
</template>

<script>
import search from './search.vue';
import notificationMenu from './notifications.vue';
import userProfile from './user-profile.vue';

export default {
    data() {
        return {
            user: {}
        }
    },

    components: { search, notificationMenu, userProfile }
}
</script>