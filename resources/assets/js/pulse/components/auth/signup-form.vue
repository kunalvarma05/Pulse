<template>
    <div class="auth-page is-long">
        <div class="container auth-container-wide">
            <a v-link="appUrl" class="auth-logo">
                <img :src="appLogo" alt="logo">
                <span class='title'>Sign Up</span>
            </a>
            <div class="auth-form">
                <form @submit.prevent="signup">
                    <div class="form-group" :class="{ 'has-danger': nameError }">
                        <label>Name</label>
                        <input class="form-control form-control-danger" v-model="name" type="text" placeholder="Name" autofocus="autofocus" required>
                        <div class="auth-error" v-show="nameError">{{ nameError }}</div>
                    </div>
                    <div class="form-group" :class="{ 'has-danger': emailError }">
                        <label>Email Address</label>
                        <input class="form-control form-control-danger" v-model="email" type="email" placeholder="Email Address" required>
                        <div class="auth-error" v-show="emailError">{{ emailError }}</div>
                    </div>
                    <div class="form-group" :class="{ 'has-danger': usernameError }">
                        <label>Username</label>
                        <input class="form-control form-control-danger" v-model="username" type="text" placeholder="Username" required>
                        <div class="auth-error" v-show="usernameError">{{ usernameError }}</div>
                    </div>
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
                    <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                </form>
            </div>
            <div class="auth-footer">
                <a href='#'> <i class="fa fa-chevron-left"></i> Home</a>
                <a v-link="{name: 'login'}" class="pull-right"> Log in <i class="fa fa-chevron-right"></i></a>
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
                name: '',
                username: '',
                password: '',
                password_confirmation: '',
                errors: false
            };
        },

        computed: {

            nameError() {
                if(this.errors) {
                    return this.errors.name ? this.errors.name[0] : '';
                }
            },

            emailError() {
                if(this.errors) {
                    return this.errors.email ? this.errors.email[0] : '';
                }
            },

            usernameError() {
                if(this.errors) {
                    return this.errors.username ? this.errors.username[0] : '';
                }
            },

            passwordError() {
                if(this.errors) {
                    return this.errors.password ? this.errors.password[0] : '';
                }
            },

        },

        methods: {

            /**
             * Sign Up
             */
             signup() {
                this.errors = false;

                userStore.store(this.name, this.email, this.username, this.password, this.password_confirmation,
                    response => {
                        //Reset the form
                        this.name = "";
                        this.email = "";
                        this.username = "";
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