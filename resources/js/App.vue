<script>

// import HelloWorld from './components/HelloWorld.vue'
// import TheWelcome from './components/TheWelcome.vue'
import Demo from './components/Demo.vue'
import ApiCallingVue from './components/ApiCalling.vue'
// import { reactive } from 'vue'
// defineEmits(['add-to-cart'])
// // var cart = 0
// const state = reactive({ cart: [], premium:false })

// function updateCart(id) {
//   state.cart.push(id)
// }
export default {
  components: { Demo, ApiCallingVue },
}
</script>
<template>
  <router-view></router-view>
</template>

<style scoped>
.navbar-nav>li>a {
  color: white;
  text-decoration: none;
  margin: 20px;
}
</style>
