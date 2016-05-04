<template>
    <div class="auth-page">
        <div class="container auth-container">
            <a v-link="appUrl" class="auth-logo">
                <img :src="appLogo" alt="logo">
                <span class='title'>Reset Password</span>
            </a>
            <div class="auth-form">
                <form @submit.prevent="resetPassword">
                    <div class="form-group" :class="{ 'has-danger': passwordError }">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>Password</label>
                                <input class="form-control form-control-danger" v-model="password" type="password" placeholder="Password" required>
                            </div>
                            <div class="col-lg-6">
                                <label>Confirm Password</label>
                                <input class="form-control form-control-danger" v-model="password_confirmation" type="password" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="auth-error" v-show="passwordError">{{ passwordError }}</div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                </form>
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
                password: '',
                password_confirmation: '',
                errors: false
            };
        },

        computed: {

            token() {
                return this.$route.query.token;
            },

            passwordError() {
                if(this.errors) {
                    return this.errors.password ? this.errors.password[0] : '';
                }
            },

        },

        methods: {

            /**
             * Reset Password
             */
             resetPassword() {
                this.errors = false;

                userStore.resetPassword(this.token, this.password, this.password_confirmation,
                    response => {
                        //Reset the form
                        this.token = "";
                        this.password = "";
                        this.password_confirmation = "";

                        //Notify the parent
                        this.$dispatch("user:loggedin");
                    },
                    errors => {
                        //Error
                        this.errors = errors;
                    }
                );
            },
        },
    };
</script>