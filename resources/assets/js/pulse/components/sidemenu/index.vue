<template>
    <nav class="sidemenu" id="sidemenu">

        <a :href="appUrl" class="sidemenu-logo">
            <img :src="appLogo" alt="pulse logo">
        </a>

        <ul class="nav sidemenu-user-accounts">

            <li v-for="account in state.accounts">
                <a class="sidemenu-user-account" @click="browseFiles(account.id)" href="#" data-toggle-tooltip="sidebar" :title="account.name" :style="'animation-delay:0.' + $index + 's';">
                    <img :src="account.picture">
                </a>
            </li>

            <li>
                <a class="sidemenu-add-button" data-toggle-tooltip="sidebar" title="Connect New Account" href="#" data-toggle="modal" data-target="#connect-account-modal">
                    <span class="fa fa-plus"></span>
                </a>
            </li>

        </ul>

    </nav>
</template>

<script>

    import Vue from 'vue';
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

        methods: {
            browseFiles: (account) => {
                accountStore.state.current = account;
                fileStore.browse(account);
            }
        },

        events: {

            "user:loggedin" : () => {
                accountStore.list();
            },

            "pulse:ready" : () => {
                accountStore.list();
            },

            "pulse:teardown" : () => {
                accountStore.init(false);
            }
        }
    }

</script>