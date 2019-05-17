import Vue from 'vue'
import Router from 'vue-router'

import Home from './components/Common/Home'
import SomePage from './components/Common/SomePage'

Vue.use(Router)

export default new Router({
  mode: 'history',  
  routes: [
    {
      path: '/',
      component: Home
    },
    {
      path: '/some-page',
      component: SomePage
    }
  ]
})