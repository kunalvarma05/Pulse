<template>
    <div class="splash-page">
        <div class="splash-main">
            <div class="container">
                <div class="splash-header">
                    <a href="#!" class="splash-logo">
                        <img :src="'/images/logo-white.png'" alt="pulse-logo">
                    </a>
                    <nav class="nav nav-inline pull-right splash-nav">
                      <a class="nav-link" href="#about">About</a>
                      <a class="nav-link" href="#features">Features</a>
                      <a class="nav-link" v-link="{name: 'login'}">Log in</a>
                  </nav>
              </div>
              <div class="splash-content">
                <div class="splash-intro">
                    <h1 class="headline">Multiple Clouds. One Platform.</h1>
                    <p class="tagline">Manage and Transfer files between Cloud Storage Services.</p>
                    <div class="actions">
                        <a href="#" class="btn btn-primary">
                            <i class="fa fa-windows icon"></i> Download for Windows
                        </a>
                        <a v-link="{name: 'signup'}" class="btn btn-success">
                            <i class="fa fa-hand-peace-o icon"></i> Get Started
                        </a>
                    </div>
                </div>
                <div class="splash-hero">
                    <img :src="'images/laptop.png'" alt="hero">
                </div>
            </div>
        </div>
    </div>
</div>
</template>