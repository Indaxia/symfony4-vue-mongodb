import '../css/app.scss'
import Vue from 'vue'
import App from './components/App'
import router from './router'

const app = new Vue({
  el: '#app',
  render: h => h(App),
  router
})