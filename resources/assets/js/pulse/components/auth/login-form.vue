<template>
    <div class="login-page">
        <div class="container login-container">
            <a v-link="appUrl" class="logo">
                <img :src="appLogo" alt="logo">
            </a>
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
            <a href="#">Forgot Password?</a>
        </div>
    </div>
</template>

<script>
    import config from '../../config';
    import userStore from "../../stores/user.js";

    export default {

        data() {
            return {
                appLogo: config.url + config.logo_dark,
                appUrl: config.url,
                email: '',
                password: '',
                failed: false
            };
        },

        methods: {

            /**
             * Log in
             */
             login() {
                this.failed = false;

                userStore.login(this.email, this.password,
                    response => {
                        //Reset the form
                        this.email = "";
                        this.password = "";

                        //Notify the parent
                        this.$dispatch("user:loggedin");
                    },
                    () => {
                        //Error
                        this.failed = true;
                    }
                    );
            },
        },
    };
</script>

<style>
    body, html{
        height: 100%;
        padding-bottom: 0 !important;
    }
    .login-page {
        height: 100%;
        background: #282c37;
        padding-top: 13%;
    }

    .login-container {
        max-width: 300px;
        background: #fff;
        border-radius: 4px;
        padding: 15px;
        text-align: center;
    }

    .page-header {
        margin-bottom: 1rem;
        border-bottom: solid 1px #dedede;
        padding-bottom: 1rem;
        margin-top: 20px;
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