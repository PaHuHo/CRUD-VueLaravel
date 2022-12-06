<script>
// import HelloWorld from './components/HelloWorld.vue'
// import TheWelcome from './components/TheWelcome.vue'
import ProductDisplay from './components/ProductDisplay.vue'
import ApiCallingVue from './components/ApiCalling.vue'
// import { reactive } from 'vue'
// defineEmits(['add-to-cart'])
// // var cart = 0
// const state = reactive({ cart: [], premium:false })

// function updateCart(id) {
//   state.cart.push(id)
// }
export default {
  components: { ProductDisplay, ApiCallingVue },
  // data() {
  //   return {
  //     cart: [],
  //     premium: true
  //   }
  // },

  // methods: {
  //   updateCart(id) {
  //     this.cart.push(id)
  //   }
  // }
}
</script>

<template>
  <div class="nav-bar navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid " style="padding-left:0;margin-left: 170px;">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#NavCollapse"
        aria-controls="NavCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="NavCollapse" style="padding-left: 0;">
        <ul class="navbar-nav">
          <li class="nav-item d-none d-sm-inline-block">
            <router-link to="/">Home</router-link>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <router-link to="/about">Demo</router-link>
          </li>
        </ul>
      </div>
    </div>

  </div>
  <!-- <ProductDisplay></ProductDisplay> -->
  <router-view></router-view>
  <!-- <ApiCallingVue></ApiCallingVue> -->
  <!-- <div class="cart">Cart( {{ cart.length }} )</div> -->

  <!-- <ProductDisplay :premium="premium" @add-to-cart="updateCart">
  </ProductDisplay> -->
</template>

<style scoped>
.navbar-nav>li>a {
  color: white;
  text-decoration: none;
  margin: 20px;
}
</style>
