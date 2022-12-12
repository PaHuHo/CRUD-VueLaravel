<template>
    <div class="nav-bar navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid " style="padding-left:0;margin-left: 170px;">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#NavCollapse"
                aria-controls="NavCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="NavCollapse" style="padding-left: 0;">

                <div class="justify-content-between" style="display: flex;">
                    <ul class="navbar-nav flex">
                        <li class="nav-item d-none d-sm-inline-block">
                            <router-link to="/">Home</router-link>
                        </li>
                        <li class="nav-item d-none d-sm-inline-block">
                            <router-link to="/about">Demo</router-link>
                        </li>
                    </ul>

                    <ul class="navbar-nav flex">
                        <li class="nav-item d-none d-sm-inline-block" style="margin-right: 10px;">
                            <span class="text-white">{{ Username }}</span>
                        </li>
                        <li class="nav-item d-none d-sm-inline-block">
                            <button @click="logout()">Logout</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    computed: {
        Username() {
            return this.user != null ? this.user.name : '';
        },
    },
    created() {
        this.getUser()
    },
    data() {
        return {
            user: null
        }
    },
    methods: {
        getUser() {
            axios.get('/api/user').then(response => {
                this.user = response.data
            }).catch(errors => {
                if (errors.response.status === 401) {
                    
                    //this.$router.push({ name: "login" });
                }
            })
        },
        logout() {
            axios.post('/api/logout').then((response) => {
                if (response.status == 200) {
                    localStorage.removeItem('token')
                    this.$router.push({ name: "login" });
                }
            })
        }
    }

}

</script>

<style>

</style>