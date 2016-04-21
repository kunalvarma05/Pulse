<template>
    <form @submit.prevent="login" :class="{ error: failed }">
        <div class="form-group">
            <input class="form-control" v-model="email" type="email" placeholder="Email Address" autofocus required>
        </div>
        <div class="form-group">
            <input class="form-control" v-model="password" type="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Log In</button>
    </form>
</template>

<script>
    import userStore from '../../stores/user';

    export default {

        data() {
            return {
                email: '',
                password: '',
                failed: false,
            };
        },

        methods: {

            login() {
                this.failed = false;

                userStore.login(this.email, this.password, () => {
                    this.failed = false;
                    // Reset the password so that the next login will have this field empty.
                    this.password = '';
                    this.$dispatch('user:loggedin');
                }, () => {
                    this.failed = true;
                });
            },
        },
    };
</script>