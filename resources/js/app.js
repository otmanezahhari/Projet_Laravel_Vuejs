require('./bootstrap');

import Vue from 'vue'
import router from './router'
import store from './store'
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import VueAxios from 'vue-axios'
import moment from 'moment'
import VueMoment from 'vue-moment'

import common from './common';
window.Vue = Vue;
Vue.use(ViewUI);
Vue.use(VueMoment, moment)
Vue.mixin(common);
Vue.use(VueAxios, axios)
Vue.component('mainapp', require('./components/mainapp.vue').default)

const app = new Vue({
    el :"#app",
    router,
    store
})

