<template>
    <nav class="sidemenu" id="sidemenu">

        <a :href="appUrl" class="sidemenu-logo">
            <img :src="appLogo" alt="pulse logo">
        </a>

        <ul class="nav sidemenu-user-accounts">

            <li v-for="account in state.accounts">
                <a class="sidemenu-user-account" v-link="{name: 'account-explorer', params: { account_id: account.id } }" data-toggle-tooltip="sidebar" :title="account.name" :style="'animation-delay:0.' + $index + 's';">
                    <img :src="account.picture">
                </a>
            </li>

            <li>
                <a class="sidemenu-add-button" data-toggle-tooltip="sidebar" title="Connect New Account" data-toggle="modal" data-target="#connect-account-modal">
                    <span class="fa fa-plus"></span>
                </a>
            </li>

        </ul>

    </nav>
</template>

<script>
    import config from '../../config';
    import userStore from '../../stores/user';
    import fileStore from '../../stores/file';
    import accountStore from '../../stores/account';

    export default {

        ready() {

        },

        data() {
            return {
                state: accountStore.state,
                appLogo: config.url + config.logo,
                appUrl: config.url,
            }
        },

        computed: {
            account_id() {
                return this.$route.params.account_id;
            }
        },

        methods: {
        },

        events: {

            "pulse:ready" : () => {
                //Fetch the Accounts of the user
                accountStore.list();
            },
        }
    }

</script>