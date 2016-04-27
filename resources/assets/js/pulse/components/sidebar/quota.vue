<template>
    <div class="sidebar-header animated slideInRight">
        Account Quota
    </div>

    <div class="sidebar-body">
        <div class="sidebar-items">
            <div class="sidebar-item">

                <div class="loading" v-show="!currentAccount">
                    Fetching account quota...
                </div>

                <div class="sidebar-item-body has-details" v-show='currentAccount'>
                    <div class="item-detail">
                        <span class="item-detail-title">Space Alloted</span>
                        <span class="item-detail-value">{{ spaceAlloted }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Space Used</span>
                        <span class="item-detail-value">{{ spaceUsed }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-title">Space Remaining</span>
                        <span class="item-detail-value">{{ spaceRemaining }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import accountStore from '../../stores/account';

    export default {
        props: [ 'account' ],

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
            },

            /**
             * Space Alloted
             * @return {string}
             */
             spaceAlloted() {
                return this.currentAccount ? this.currentAccount.quota.space_alloted : '';
            },

            /**
             * Space Used
             * @return {string}
             */
             spaceUsed() {
                return this.currentAccount ? this.currentAccount.quota.space_used : '';
            },

            /**
             * Space Remaining
             * @return {string}
             */
             spaceRemaining() {
                return this.currentAccount ? this.currentAccount.quota.space_remaining : '';
            },

        }

    }
</script>