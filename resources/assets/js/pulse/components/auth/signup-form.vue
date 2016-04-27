<template>
    <div class="container signup-container">
        <form @submit.prevent="signup" :class="{ 'has-danger': errors }">
            <h2 class="page-header">Sign Up</h2>
            <div class="form-group">
                <label>Name</label>
                <input class="form-control form-control-danger" v-model="name" type="text" placeholder="Name" autofocus="autofocus" required>
                <div class="form-error" v-show="nameError">{{ nameError }}</div>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input class="form-control form-control-danger" v-model="email" type="email" placeholder="Email Address" required>
                <div class="form-error" v-show="emailError">{{ emailError }}</div>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input class="form-control form-control-danger" v-model="username" type="text" placeholder="Username" required>
                <div class="form-error" v-show="usernameError">{{ usernameError }}</div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <label>Password</label>
                        <input class="form-control form-control-danger" v-model="password" type="password" placeholder="Password" required>
                    </div>
                    <div class="col-lg-6">
                        <label>Confirm Password</label>
                        <input class="form-control form-control-danger" v-model="password_confirmation" type="password" placeholder="Confirm Password" required>
                    </div>
                </div>
                <div class="form-error" v-show="passwordError">{{ passwordError }}</div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
        </form>
    </div>
</template>

<script>
    import userStore from "../../stores/user.js";

    export default {

        data() {
            return {
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

<style>
    .signup-container {
        margin-top: 10%;
        max-width: 400px;
    }

    .page-header {
        margin-bottom: 1rem;
        border-bottom: solid 1px #dedede;
        padding-bottom: 1rem;
    }

    .form-error {
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 3px;
        display: block;
        color: #fff;
        background: #d9534f;
        margin-top: 10px;
        text-align: center;
    }

</style>