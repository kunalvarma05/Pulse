<template>
    <div class="dropdown-menu dropdown-menu-right notifications-dropdown">
        <div class="dropdown-header">
            Notifications
            <div class="pull-right">
                <a href="#">View All</a>
            </div>
        </div>
        <div class="notification-items">
            <h5 v-show="notifications" class="text-center">No Notifications!</h5>

            <a v-for="notification in notifications" href="#" class="dropdown-item media notification-item">
                <span class="media-left">
                    <img class="media-object" v-bind:src="notification.user.picture" alt="img">
                </span>
                <div class="media-body">
                    <b>{{notification.file.key}}</b> {{notification.message}}
                    <span class="timestamp">{{notification.created_at}}</span>
                </div>
            </a>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                notifications: []
            };
        }
    }
</script>