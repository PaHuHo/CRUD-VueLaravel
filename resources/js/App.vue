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
  data() {
    return {
      cart: [],
      premium: true
    }
  },

  methods: {
    updateCart(id) {
      this.cart.push(id)
    }
  }
}
</script>

<template>
  <div class="nav-bar"></div>

  <ApiCallingVue></ApiCallingVue>
  <!-- <div class="cart">Cart( {{ cart.length }} )</div> -->

  <!-- <ProductDisplay :premium="premium" @add-to-cart="updateCart">
  </ProductDisplay> -->
</template>

<!-- <style scoped>
header {
  line-height: 1.5;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }
}
</style> -->
