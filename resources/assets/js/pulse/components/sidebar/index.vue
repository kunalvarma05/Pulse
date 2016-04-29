<template>

    <div class="sidebar" @click.stop="">
        <div v-show='file'>
            <file-info :file.sync='file' :account.sync='currentAccount'></file-info>
        </div>
        <div v-show='!file'>
            <quota :account.sync='currentAccount'></quota>
        </div>
    </div>

</template>

<script>

    import accountStore from '../../stores/account';

    import fileInfo from './fileinfo.vue';
    import quota from './quota.vue';

    export default {

        props: [ 'file', 'account' ],

        components: { fileInfo, quota },

        data() {
            return {
                state: {
                    accountStore: accountStore.state,
                }
            };
        },

        computed: {

            /**
             * Current Account
             * @return {Object}
             */
             currentAccount() {
                return this.account;
            }

        }

    }

</script>