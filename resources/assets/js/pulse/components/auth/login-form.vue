<template>
    <div class="auth-page">
        <div class="container auth-container">
            <a v-link="appUrl" class="auth-logo">
                <img :src="appLogo" alt="logo">
                <span class='title'>Log in</span>
            </a>
            <div class="auth-form">
                <form @submit.prevent="login" :class="{ 'has-danger': failed }">
                    <div class="form-group">
                        <input class="form-control form-control-danger" v-model="email" type="email" placeholder="Email Address" autofocus required>
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-danger" v-model="password" type="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Log In</button>
                    <div class="auth-error" v-show="failed">Invalid Email or Password</div>
                </form>
            </div>
            <div class="auth-footer">
                <a v-link="{name: 'signup'}"> <i class="fa fa-chevron-left"></i> Sign Up</a>
                <a class="pull-right" href="#">Forgot Password? <i class="fa fa-chevron-right"></i></a>
            </div>
        </div>
    </div>
</template>

<script>
    import config from '../../config';
    import userStore from "../../stores/user.js";

    export default {

        data() {
            return {
                appLogo: config.url + config.logo,
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