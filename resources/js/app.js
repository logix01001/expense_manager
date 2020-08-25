
require('./bootstrap');

window.Vue = require('vue');

import Vuex from 'vuex'
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'

window.iziToast = require('iziToast');

Vue.use(Vuex)
Vue.use(Vuetify)
Vue.use(VueRouter);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import router from '@/js/routes/router'

import App from '@/js/components/Baseapp'

import store from '@/js/store';

//USER GATES PERMISSION
Vue.prototype.$gates = new Gates(window.user)

router.beforeEach((to, from, next) => {

    //CHECK IF USER IS ALREADY LOGIN
    if (to.matched.some(record => record.meta.requiresLogin) && Vue.prototype.$gates.user == undefined) {
        next("/login")
    }
    //CHECK IF USER HAS ANY PERMISSION ON SPECIFIC PAGE //CREATE UPDATE OR DELETE
    else if (to.matched.some(record => record.meta.permission) && Vue.prototype.$gates.hasAnyPermission(to.meta.permission) == false) {
        next("/page403")
    }
    else {
        next()
    }
})

//CSRF TOKEN
Vue.prototype.$csrf = document.querySelector('meta[name=csrf-token]').content

import Gates from '@/js/class/gates'
const app = new Vue({
    components:{
        App,
    },
    el: '#app',
    router,
    store,
    vuetify: new Vuetify()


});
