// app.js
import { createApp } from 'vue'
require('./bootstrap');
// import Vue from 'vue/dist/vue';
// import VueRouter from './router';
// Vue.use(require('vue-router'));
// const VueRouter = require('vue-router').default;
import VueAxios from 'vue-axios';
import axios from 'axios';

import App from './App.vue';
//import LaravelVuePagination from  'laravel-vue-pagination';
import Pagination from 'v-pagination-3';
// import HomeComponent from './components/HomeComponent.vue';
// import CreateComponent from './components/CreateComponent.vue';
// import IndexComponent from './components/IndexComponent.vue';
// import EditComponent from './components/EditComponent.vue';

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
//   {
//       name: 'edit',
//       path: '/edit/:id',
//       component: EditComponent
//   }
// ];

// const router = new VueRouter({ mode: 'history', routes: routes});

const app=createApp(App).use(VueAxios, axios);
// app.component('pagination', require('laravel-vue-pagination')); 
app.component('pagination', Pagination);
app.mount('#app')