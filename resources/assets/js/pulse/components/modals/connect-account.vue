<template>
  <div class="modal fade" id="connect-account-modal" tabindex="-1" role="dialog" aria-labelledby="connectAccountLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="connectAccountLabel">Connect Account</h4>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row connect-account-items">

              <div v-for="provider in state.providers" class="col-md-6 col-sm-6 connect-account-item">
                <div class="card">
                  <div class="card-block text-xs-center">
                    <p class="card-text">
                      <img v-bind:src="'/images/providers/' + provider.alias + '.png'" alt="dropbox" class="connect-account-image">
                    </p>
                    <h4 class="card-title">
                      {{provider.title}}
                    </h4>
                  </div>
                  <div class="card-footer">
                    <a href="#" @click="connect(provider.alias)" class="btn btn-primary btn-block" data-toggle="button" data-loading="">Connect</a>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

    ready() {
    },

    data() {
      return {
        state: providerStore.state,
      };
    },

    methods: {
      connect(provider_alias) {
        providerStore.authUrl(provider_alias, (url) => {
          window.location = url;
        });
      }
    },

    events: {
      "user:loggedin" : () => {
        providerStore.list();
      },

      "pulse:ready" : () => {
        providerStore.list();
      },

      "pulse:teardown" : () => {
        providerStore.init([]);
      }
    }

  }
</script>