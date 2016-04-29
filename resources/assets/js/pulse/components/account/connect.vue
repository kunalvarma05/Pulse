<template>
  <div class="connect-account container">
    <h2 class="page-header">Connect Account</h2>
    <div class="connect-account-items">
      <div v-for="provider in state.providers" class="col-md-6 col-sm-6 connect-account-item">
        <div class="card">
          <div class="card-block connect-account-item-inner">
            <div class="card-text clearfix">
              <div class="col-lg-4 col-md-4">
                <img v-bind:src="'/images/providers/' + provider.alias + '.png'" alt="dropbox" class="connect-account-image">
              </div>
              <div class="col-lg-8 col-md-8">
                <h4 class="card-title connect-account-title">
                  {{provider.name}}
                </h4>
                <div class="connect-account-description">
                  {{provider.description}}
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <a href="#" @click="connect(provider.alias)" class="btn btn-primary btn-block">Connect</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import config from '../../config'
  import userStore from '../../stores/user';
  import providerStore from '../../stores/provider';

  export default {

    data() {
      return {
        state: providerStore.state,
      };
    },

    route: {
      data() {

        //Provider's once loaded
        //don't need to be loaded again
        if(!providerStore.providers) {
          providerStore.list();
        }

      },
    },

    methods: {
      connect(provider_alias) {
        providerStore.authUrl(provider_alias, (url) => {
          window.location = url;
        });
      }
    },

    events: {

      "pulse:teardown" : () => {
        providerStore.init([]);
      }

    }

  }
</script>

<style>
  .connect-account {
    padding-top: 20px;
  }
</style>