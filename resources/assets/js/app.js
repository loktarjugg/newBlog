
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import App from './App.vue';
import ElementUI from 'element-ui'
import VueRouter from 'vue-router'
import 'element-ui/lib/theme-default/index.css'
import routes from './routes';
import store from './vuex/store';
import VueProgressBar from 'vue-progressbar';
Vue.use(ElementUI);
Vue.use(VueRouter);
Vue.use(VueProgressBar, {
    color: '#00a626',
    failedColor: 'red',
    height: '2px',
    thickness: '2px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
});


const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    linkActiveClass: 'active',
    saveScrollPosition: true,
    root: '/admin',
    routes: routes
});

router.beforeEach(function (to, from, next) {
    store.commit('UPDATE_LOADING_STATUS', { isLoading: true });
    next()
});

router.afterEach(function (to) {
    store.commit('UPDATE_LOADING_STATUS', {isLoading: false});
});

const app = new Vue(Vue.util.extend({ router,store},App)).$mount('#app');