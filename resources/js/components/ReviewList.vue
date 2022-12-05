<script>
export default {
    props: {
        reviews: {
            type: Array,
            required: true
        }
    },
}

</script>
<template>
    <div class="review-container">
        <h3>Review:</h3>
        <ul>
            <li v-for="(review, index) in reviews" :key="index">
                {{ review.name }} gave this {{ review.rating }}
                <br>
                "{{ review.review }}"
            </li>
        </ul>
    </div>
</template>