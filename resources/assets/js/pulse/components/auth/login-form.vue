<template>
    <div class="container login-container">
        <form @submit.prevent="login" :class="{ 'has-danger': failed }">
            <h2 class="page-header">Log in</h2>
            <div class="form-group">
                <input class="form-control form-control-danger" v-model="email" type="email" placeholder="Email Address" autofocus required>
            </div>
            <div class="form-group">
                <input class="form-control form-control-danger" v-model="password" type="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Log In</button>
            <div class="login-text-help" v-show="failed">Invalid Email or Password</div>
        </form>
    </div>
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

<style>
    .login-container {
        margin-top: 10%;
        max-width: 300px;
    }

    .login-text-help {
        padding: 5px 10px;
        border-radius: 3px;
        display: block;
        color: #fff;
        background: #d9534f;
        margin-top: 10px;
        text-align: center;
    }

</style>