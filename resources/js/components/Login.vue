<template>
    <div class="container">
        <div class="box">
            <div class="alert alert-danger alert-dismissible" role="alert" v-if="errors.length > 0">
                <!-- <b>{{ errors.message }}</b>
                <ul>
                    <li v-for="(errorName, index) in errors.errors" :key="index">
                        {{ errorName[0] }}
                    </li>
                </ul>
                <button type="button" class="close" @click="error = null"
                    style="position: absolute;top: 10px;right: 20px;">
                    <span aria-hidden="true">×</span>
                </button> -->
                <!-- <span v-for="error in errors" >{{ error }}</span> -->
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>
            <form>
                <div class="row">
                    <div>
                        <label for="email" class="font-weight-bold">Email</label>
                        <input v-model="auth.email" type="text" class="form-control" name="email" placeholder="Email"
                            aria-label="Username" aria-describedby="addon-wrapping">
                        <!-- <span class="text-red" v-if="errors.name">{{errors.name[0]}}</span> -->
                    </div>
                </div>
                <br>
                <div class="row">
                    <div>
                        <label for="password" class="font-weight-bold">Password</label>
                        <input v-model="auth.password" type="password" class="form-control" name="password"
                            placeholder="Password" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                </div>

                <br>
                <div class="row">
                    <!-- <div class="col">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="rememberCheck" value="1">
                            <label class="form-check-label">Remember me</label>
                        </div>
                    </div> -->
                    <div class="col-sm-4">
                        <button @click.prevent="login" type="submit" class="btn btn-primary"
                            style="margin-left: 13px;">Đăng nhập</button>
                    </div>
                </div>

            </form>
        </div>

    </div>
</template>

<script>
export default {
    name: "login",
    data() {
        return {
            auth: {
                email: "",
                password: ""
            },
            errors: []
        }
    },
    methods: {
        async login() {
            try {
                axios.post("/api/login", this.auth).then(response => {
                    console.log(response.data);                 
                    if (response.data.status === 'success') {
                        console.log(response.data.user)
                        localStorage.setItem('token',response.data.user.email)
                        this.$router.push({ name: "home" });
                    }
                    if (response.data.status == 'error') {
                        this.errors = response.data.message;
                    }
                })
            } catch (error) {
                this.errors = error.response.data.errors;
            }

            // await axios.post('/api/login', {
            //     email: this.auth.email,
            //     password: this.auth.password,
            // }).then((response) => {
            //     if (response .status === 200) {
            //         this.$router.push({ name: "home" });
            //     }
            // }).catch((error) => {
            //     this.error = error.response.data.errors;
            // })
        },
    }
}
</script>

<style scoped>
.container {
    margin: 200px;
    padding: 50px 400px 50px 400px;
}
</style>