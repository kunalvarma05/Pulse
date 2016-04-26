<template>
    <div class="sidebar-header animated slideInRight">
        Account Quota
    </div>

    <div class="sidebar-body">
        <div class="sidebar-items">
            <div class="sidebar-item" style="animation-delay: 0.5s;">
                <div class="sidebar-item-body has-details">
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
                    quota: ''
                }
            };
        },

        ready() {
            //Get Account Quota
            accountStore.quota(this.account_id,
                (quota) => {
                    //Set the account quota
                    this.state.quota = quota;
                }
            );
        },

        computed: {

            /**
             * Account Id
             * @return {int}
             */
             account_id() {
                return this.$route.params.account_id;
            },

            /**
             * Space Alloted
             * @return {string}
             */
             spaceAlloted() {
                return this.state.quota.space_alloted;
            },

            /**
             * Space Used
             * @return {string}
             */
             spaceUsed() {
                return this.state.quota.space_used;
            },

            /**
             * Space Remaining
             * @return {string}
             */
             spaceRemaining() {
                return this.state.quota.space_remaining;
            },

        }

    }
</script>