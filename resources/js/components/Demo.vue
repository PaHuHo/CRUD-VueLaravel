<script>
import ProductDisplay from './ProductDisplay.vue'
import NavBarVue from './NavBar.vue'
export default {
  components: { ProductDisplay,NavBarVue },
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
  <NavBarVue></NavBarVue>
   
  <div class="cart">Cart( {{ cart.length }} )</div>

  <ProductDisplay :premium="premium" @add-to-cart="updateCart">
  </ProductDisplay>
</template>

<style scoped>
.navbar-nav>li>a {
  color: white;
  text-decoration: none;
  margin: 20px;
}
</style>
