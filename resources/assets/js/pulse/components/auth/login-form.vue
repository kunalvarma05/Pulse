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
    import userStore from "../../stores/user.js";
    export default {

        data() {
            return {
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
    .login-container {
        margin-top: 10%;
        max-width: 300px;
    }

    .page-header {
        margin-bottom: 1rem;
        border-bottom: solid 1px #dedede;
        padding-bottom: 1rem;
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