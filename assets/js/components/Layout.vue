<template>
    <div class="layout">
        <h1>It works!</h1>
        <ul class="navigation">
            <li><router-link to="/">Home</router-link></li>
            <li><router-link to="/some-page">Some Page</router-link></li>
            <li v-if="!error">{{ clientLocale ? 'Your language: '+clientLocale : 'Loading ...' }}</li>
            <li v-if="error">Error: {{ error }}</li>
        </ul>
        <router-view></router-view>
    </div>
</template>

<script>
    import axios from 'axios';
    
    export default {
        data() {
            return {
                clientLocale: '',
                error: ''
            }
        },
        mounted() {
            axios
              .get('/api/initial')
              .then(
                response => (this.clientLocale = response.data.clientLocale),
                error => (this.error = response.statusText)
              );
        }
    }
</script>

<style scoped>
  .layout {
    text-align: center;
    max-width: 800px;
  }
  .navigation {
    text-align: left;
  }
</style>