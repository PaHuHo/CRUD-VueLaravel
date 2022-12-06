// app.js
import { createApp } from 'vue'
require('./bootstrap');
// import Vue from 'vue/dist/vue';
// import VueRouter from './router';
// Vue.use(require('vue-router'));

import VueAxios from 'vue-axios';
import axios from 'axios';

import App from './App.vue';
//import LaravelVuePagination from  'laravel-vue-pagination';
import Pagination from 'v-pagination-3';
import HomeComponent from './components/HomeComponent.vue';
import CreateComponent from './components/CreateComponent.vue';
import IndexComponent from './components/IndexComponent.vue';
import Product from './components/ApiCalling.vue';
import About from './components/ProductDisplay.vue';

const VueRouter = require('vue-router');
const routes = [
    { name: 'home', path: '/', component: Product },
    {  name: 'about', path: '/about', component: About },
  ]
  const router = VueRouter.createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: VueRouter.createWebHashHistory(),
    routes, // short for `routes: routes`
  })
// const routes = [
//   {
//       name: 'home',
//       path: '/',
//       component: HomeComponent
//   },
//   {
//       name: 'create',
//       path: '/create',
//       component: CreateComponent
//   },
//   {
//       name: 'posts',
//       path: '/posts',
//       component: IndexComponent
//   },
// ];

// const router = new VueRouter({ mode: 'history', routes: routes});
// console.log(router);

const app = createApp(App);
app.use(router)
app.use(VueAxios,axios)
app.component('pagination', Pagination);
app.mount('#app');
// app.component('pagination', require('laravel-vue-pagination')); 