<script>

export default {
  emits: ['review-submitted'],
  data() {
    return {
      name: '',
      review: '',
      rating: null
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
      }

      let productReview = {
        name: this.name,
        review: this.review,
        rating: this.rating,
      }
      this.$emit('review-submitted', productReview)

      this.name = ''
      this.review = ''
      this.rating = null
    }
  }
}
</script>

<template>
  <form class="review-form" @submit.prevent="onSubmit">  
    <h3>Leave a review</h3>

    <label for="name">Name:</label>

    <!--  v-model giup lien ket voi ten bien dc tao o data() -->
    <input id="name" v-model="name">

    <label for="review">Review:</label>
    <textarea   id="review" v-model="review"></textarea>

    <label for="rating">Rating:</label>
    <select id="rating" v-model.number="rating">
      <option>5</option>
      <option>4</option>
      <option>3</option>
      <option>2</option>
      <option>1</option>
    </select>

    <input class="button" type="submit" value="Submit">
  </form>
</template>
