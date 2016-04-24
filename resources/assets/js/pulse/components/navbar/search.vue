<template>
    <form class="navbar-search-form col-lg-5 col-md-5 col-sm-9 col-xs-9">
        <input class="form-control navbar-search-input" v-model="query" type="text" placeholder="Search">
    </form>
</template>

<script>
    export default {
        data() {
            return {
                query: ""
            }
        }
    }
</script>