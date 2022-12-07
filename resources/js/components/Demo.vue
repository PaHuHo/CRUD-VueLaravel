<script>
import ProductDisplay from './ProductDisplay.vue'
export default {
  components: { ProductDisplay },
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
