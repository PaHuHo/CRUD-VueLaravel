<script>
import ReviewForm from './ReviewForm.vue'
import ReviewList from './ReviewList.vue'
// import Vue from 'vue'
// import Vuex from 'vuex'

// Vue.use(Vuex)
export default{
    components:{ReviewForm,ReviewList},
    emits: ['add-to-cart','review-submitted'],
    props: {
        premium: {
            type: Boolean,
            required: true
        }
    },
    data() {
        return {

            name: '',
            review: '',
            rating: null,

            brand: 'Vue',
            product: 'Giày Nam',
            selectedVariant: 0,
            details: ['50% cotton', '30% wool', '20% polyesfer'],

            variants: [
                { id: 2234, color: "green", image: 'img/product-1.jpg', quantity: 15 },
                { id: 2235, color: "brown", image: 'img/product-2.jpg', quantity: 0 }
            ],
            reviews: [

            ],

        }
    },

    methods: {
        onSubmit() {
            if (this.name === "" && this.review === "" && this.rating === null) {
                swal({
                    title: "Oops",
                    text: "Vui lòng xem lại review",
                    type: "error",
                    confirmButtonColor: "Green",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true,
                })
            } else {
                let productReview = {
                    name: this.name,
                    review: this.review,
                    rating: this.rating,
                }
                this.reviews.push(productReview)
            }
            this.name = ''
            this.review = ''
            this.rating = null
        },
        addToCart() {
            //$Emit giúp gửi event qua cho html thông qua @<tên bên trong emit> ở thuộc tính thể qua đó ta có thể làm 1 cái method có tên giống vậy

            //Tham số thứ 2 giúp truyền dữ liệu qua bên method
            this.$emit("add-to-cart", this.variants[this.selectedVariant].id)
        },
        addReview(review) {
            this.reviews.push(review)
        },
        updateVariant(index) {
            this.selectedVariant = index
        }
    },
    // computed giúp tăng hiệu suất
    computed: {
        title() {
            return this.brand + " " + this.product
        },
        image() {
            return this.variants[this.selectedVariant].image
        },
        inventory() {
            return this.variants[this.selectedVariant].quantity
        },
        shipping() {
            if (this.premium) {
                return 'Free'
            }
            return 2.99
        }
    }
}
</script>

<template>
    <div class="product-display">
        <div class="product-container">
            <div class="product-image">
                <img v-bind:src="image">
            </div>
            <div class="product-info">
                <h1>{{ title }}</h1>

                <p v-if="inventory > 10"> In Stock</p>
                <p v-else-if="inventory > 0 && inventory <= 10"> Almost sold out!</p>
                <p v-else> Out of Stock</p>

                <p>Shipping: {{ shipping }}</p>
                <ul>
                    <li v-for="(detail, index) in details">{{ (index + 1) + '. ' + detail }}</li>
                </ul>
                <div class="color-circle" :style="{ backgroundColor: variant.color }"
                    v-for="(variant, index) in variants" :key="variant.id" @mouseover="updateVariant(index)"></div>

                <button v-on:click="addToCart" class="button" :class="{ disabledButton: inventory <= 0 }"
                    :disabled="inventory <= 0">Add to Cart</button>

            </div>
        </div>
        <ReviewList v-if="reviews.length" :reviews="reviews"/>
        <ReviewForm @review-submitted="addReview"/>
    </div>
</template>