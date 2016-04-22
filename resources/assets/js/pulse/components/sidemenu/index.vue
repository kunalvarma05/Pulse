<template>
    <nav class="sidemenu" id="sidemenu">

        <a href="{{ appUrl }}" class="sidemenu-logo">
            <img v-bind:src="appLogo" alt="pulse logo">
        </a>

        <ul class="nav sidemenu-user-accounts">

            <li v-for="account in accounts">
                <a class="sidemenu-user-account" data-toggle-tooltip="tooltip" title="{{account.title}}" style="animation-delay: .{{$index}}s;">
                    <img v-bind:src="{{ account.picture }}">
                </a>
            </li>

            <li>
                <a class="sidemenu-add-button" data-toggle-tooltip="tooltip" title="Connect New Account" href="#" data-toggle="modal" data-target="#connect-account-modal">
                    <span class="fa fa-plus"></span>
                </a>
            </li>

        </ul>

    </nav>
</template>

<script>

    import config from '../../config'
    import accountStore from '../../stores/account';

    export default {

        init() {
            accountStore.list();
        },

        data() {
            return {
                state: accountStore.state,
                appLogo: config.url + config.logo,
                appUrl: config.url,
            }
        }
    }

</script>