<template>
    <div class="container create-account-container">
        <form @submit.prevent="create" :class="{ 'has-danger': errors }">
            <h2 class="page-header">Create Account</h2>
            <div class="form-group">
                <label>Name</label>
                <input class="form-control form-control-danger" v-model="name" type="text" placeholder="Name" autofocus="autofocus" required>
                <div class="form-error" v-show="nameError">{{ nameError }}</div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
        </form>
    </div>
</template>

<script>
    import accountStore from "../../stores/account.js";

    export default {

        data() {
            return {
                name: '',
                code: '',
                state: '',
                provider: '',
                errors: false
            };
        },

        computed: {

            nameError() {
                if(this.errors) {
                    return this.errors.name ? this.errors.name[0] : '';
                }
            },

            code() {
                return this.$route.query.code;
            },

            state() {
                return this.$route.query.state;
            },

            provider() {
                return this.$route.params.provider;
            },

        },

        methods: {

            /**
             * Connect
             */
             create() {
                this.errors = false;

                accountStore.create(this.name, this.provider, this.code, this.state,
                    account => {
                        //Reset the form
                        this.name = "";

                        this.$route.router.go({ name: 'account-explorer', params: { account_id: account.id } });

                        //Notify the parent
                        this.$dispatch("account:created");
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
    .create-account-container {
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