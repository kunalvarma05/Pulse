<template>
    <div class="error-notifier" v-show="hasErrors">
        <div class="error-notification" v-for="error in errors" @click.stop="clear(error)">
            {{error}}
        </div>
    </div>
</template>

<script>
    import sharedStore from '../../stores/shared.js';

    export default {

        data() {
            return {
                state: {
                    errors: sharedStore.state.errors
                }
            };
        },

        computed: {

            errors() {
                return this.state.errors;
            },


            hasErrors() {
                return this.errors.length > 0 ? true : false;
            }

        },

        methods: {
            clear(error) {
                this.state.errors.$remove(error);
            }
        }

    }
</script>