<template>
    <div class="auth-page">
        <div class="container auth-container">
            <a v-link="appUrl" class="auth-logo">
                <img :src="appLogo" alt="logo">
                <span class='title'>Forgot Password</span>
            </a>
            <div class="auth-form">
                <form @submit.prevent="forgotPassword" v-show="!sent">
                    <div class="form-group" :class="{ 'has-danger': emailError }">
                        <label>Email Address</label>
                        <input class="form-control form-control-danger" v-model="email" type="email" placeholder="Email Address" required>
                        <div class="auth-error" v-show="emailError">{{ emailError }}</div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                </form>
                <div class="auth-success" v-show='sent'>An email with instructions to reset your password was sent!</div>
            </div>
            <div class="auth-footer">
                <a v-link="{name: 'login'}"><i class="fa fa-chevron-left"></i> Log in </a>
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
                sent: false,
                errors: false
            };
        },

        computed: {

            emailError() {
                if(this.errors) {
                    return this.errors.email ? this.errors.email[0] : '';
                }
            },

        },

        methods: {

            /**
             * Forgot Password
             */
             forgotPassword() {
                this.errors = false;

                userStore.forgotPassword(this.email,
                    response => {
                        this.sent = true;
                        //Reset the form
                        this.email = "";
                    },
                    errors => {
                        this.sent = false;
                        //Error
                        this.errors = errors;
                    }
                    );
            },
        },
    };
</script>