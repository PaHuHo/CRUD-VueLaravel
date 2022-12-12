// app.js
import { createApp, nextTick } from "vue";
require("./bootstrap");
// import Vue from 'vue/dist/vue';
// import VueRouter from './router';
// Vue.use(require('vue-router'));

import VueAxios from "vue-axios";
import axios from "axios";

import App from "./App.vue";
//import LaravelVuePagination from  'laravel-vue-pagination';
import Pagination from "v-pagination-3";
import Product from "./components/ApiCalling.vue";
import About from "./components/Demo.vue";
import Login from "./components/Login.vue";

const VueRouter = require("vue-router");
const routes = [
    { name: "home", path: "/", component: Product ,meta:{
        requiresAuth:true
    }},
    { name: "about", path: "/about", component: About,meta:{
        requiresAuth:true
    }},
    { name: "login", path: "/login", component: Login,meta:{
        requiresAuth:false
    } },
];
const router = VueRouter.createRouter({
    // 4. Provide the history implementation to use. We are using the html5 history for simplicity here.
    history: VueRouter.createWebHistory(),
    routes, // short for `routes: routes`
});
router.beforeEach((to, from,next)=>{
    if(to.meta.requiresAuth && !localStorage.getItem('token')){          
        return next({path:'/login'})
    }else if(!to.meta.requiresAuth && localStorage.getItem('token')){
        return next({path:'/'})
    }else{
        next()
    }
});
// router.beforeEach((to, from, next)=>{
//     if(to.matched.some(record=>record.meta.requiresAuth)){          
//         if(!localStorage.getItem('token')){
//             return {name:'login'}
//         }else{
//             next()
//         }
//     }else if(to.matched.some(record=>record.meta.guest)){
//         if(localStorage.getItem('token')){
//             return {name:'home'}
//         }else{
//             next()
//         }
//     }else{
//         next()
//     }
// });
const app = createApp(App);
app.use(router);
app.use(VueAxios, axios);
app.component("pagination", Pagination);
app.mount("#app");
// app.component('pagination', require('laravel-vue-pagination'));
