<template>
  <div class="hero">
    Hello, {{ message }}!
    <br>
    <input type="text" v-model="message">
  </div>
</template>

<script>
  export default {
    data () {
      return {
        message: 'Hello World!'
      };
    }
  };
</script>

<style>
  .hero {
    background: #eee;
    padding: 1em;
  }
</style>