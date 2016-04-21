<template>
    <li class="nav-item dropdown notifications-menu unread">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
            <span class="label label-primary notification-count animated zoomIn" v-show="count">{{ count }}</span>
            <i class="fa fa-bell"></i>
        </a>

        <notification-dropdown></notification-dropdown>

    </li>
</template>
<script>
    import notificationDropdown from './notification-dropdown.vue';

    export default {

        components: { notificationDropdown },

        data() {
            return {
                count: 0
            };
        }
    }
</script>