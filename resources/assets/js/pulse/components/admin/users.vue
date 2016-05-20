<template>
    <h4>Users</h4>

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Username</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in users">
                <th scope="row">{{user.id}}</th>
                <td>{{user.name}}</td>
                <td>{{user.username}}</td>
                <td>{{user.created_at}}</td>
                <td>
                    <a class="btn btn-sm btn-danger" @click="deleteUser(user.id)">Delete</a>
                </td>
            </tr>
        </tbody>
    </table>

</template>

<script>

    //User Store
    import userStore from '../../stores/user.js';

    export default {

        data() {
            return {
                state: {
                    userStore: userStore.state
                },
            };
        },

        computed: {
            users() {
                return this.state.userStore.users;
            }
        },

        route: {
            data() {
                userStore.getUsers();
            }
        },

        methods: {
            deleteUser(id) {
                userStore.delete(id);
            }
        }

    }
</script>