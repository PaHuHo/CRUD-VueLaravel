<template>
    <div class="api-calling container mt-5">
        <h1>Search Product</h1>
        <div class="form-group">
            <label>Name</label>
            <input v-model="searchProduct.name" type="text" class="form-control" placeholder="Tìm kiếm tên...">
        </div>
        <div class="form-group">
            <label>Price</label>
            <input v-model="searchProduct.price" type="text" class="form-control" placeholder="Tìm kiếm giá...">
        </div>
        <button class="btn btn-primary" @click="onClickCreate()" style="margin-right:10px">Add Product</button>
        <button class="btn btn-primary" @click="getSearchProduct()" style="margin-right:10px">Search</button>
        <button class="btn btn-success" @click="deleteSearchProduct()">Xóa search</button>
        <hr>
        <h1>List Products</h1>
        <table class="table">
            <thead>
                <tr class="table-primary" style="--bs-table-bg: #3395de; color:white">
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody v-if="(listProducts.total > 0)">
                <div style="display:none">{{ (i = (listProducts.current_page - 1) * listProducts.per_page + 1) }}</div>
                <tr v-for="(product, index) in listProducts.data" :key="product.id">
                    <th scope="row">{{ i++ }}</th>
                    <td>
                        {{ product.name }}
                    </td>
                    <td>
                        ${{ product.price }}
                    </td>
                    <td>
                        <button class="btn btn-primary" @click="onClickEdit(product, index)"
                            style="margin-right:10px">Edit</button>
                        <button class="btn btn-danger" @click="deleteProduct(product, index)">Delete</button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6" style="text-align: center;">Không có dữ liệu</td>
                </tr>
            </tbody>
        </table>
        <!-- <div>
            {{ listProducts.from }} - {{ listProducts.to }} of {{ listProducts.total }}
        </div> -->
        <!-- <ul class="pagination">
            <li class="page-item" :class="{ 'disabled': listProducts.prev_page_url === null }"
                @click="listProducts.prev_page_url && getListProducts(listProducts.current_page - 1)">
                <a class="page-link" href="#">Previous</a>
            </li>
            <li class="page-item" v-if="listProducts.prev_page_url"
                @click="getListProducts(listProducts.current_page - 1)">
                <a class="page-link" href="#">{{ listProducts.current_page - 1 }}</a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">{{ listProducts.current_page }}</a>
            </li>
            <li class="page-item" v-if="listProducts.next_page_url"
                @click="getListProducts(listProducts.current_page + 1)">
                <a class="page-link" href="#">{{ listProducts.current_page + 1 }}</a>
            </li>
            <li class="page-item" :class="{ 'disabled': listProducts.next_page_url === null }"
                @click="listProducts.next_page_url && getListProducts(listProducts.current_page + 1)">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul> -->
        <!-- <pagination :data="listProducts" @pagination-change-page="getListProducts()"></pagination> -->
        <div id="pagination" v-if="(listProducts.total > 5)">
            <pagination v-model="page" :records="Number(listProducts.total)" :per-page="Number(listProducts.per_page)"
                @paginate="getSearchProduct(page)" />
        </div>
    </div>
    <!-- Model Create -->
    <div class="modal fade" id="createForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div :show="isOpenModel" class="alert alert-danger alert-dismissible" role="alert" v-if="error">
                    <b>{{ error.message }}</b>
                    <ul>
                        <li v-for="(errorName, index) in error.errors" :key="index">
                            {{ errorName[0] }}
                        </li>
                    </ul>
                    <button type="button" class="close" @click="error = null" style="position: absolute;top: 10px;right: 20px;">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa</h5>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="createProduct" style="padding:10px">
                        <!-- <div class="form-group">
                            <label>Name</label>
                            <input v-model="product.name" type="text" class="form-control" placeholder="Name...">
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input v-model="product.price" type="text" class="form-control" placeholder="Price...">
                        </div>
                        <button class="btn btn-primary" @click="createProduct">Create</button> -->
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Tên</label>
                            <div class="col">
                                <input v-model="product.name" type="text" class="form-control"
                                    placeholder="Nhập tên...">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="price" class="col-sm-2 col-form-label">Giá</label>
                            <div class="col">
                                <input v-model="product.price" type="text" class="form-control"
                                    placeholder="Nhập giá...">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col" style="display: flex;justify-content: flex-end;">
                                <button type="button" class="btn btn-secondary" @click="closeModel(1)">Hủy</button>
                                <button type="submit" class="btn btn-danger" style="margin-left:20px">Lưu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Model Update -->
    <div class="modal fade" id="editForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div :show="isOpenModel" class="alert alert-danger alert-dismissible" role="alert" v-if="error">
                    <b>{{ error.message }}</b>
                    <ul>
                        <li v-for="(errorName, index) in error.errors" :key="index">
                            {{ errorName[0] }}
                        </li>
                    </ul>
                    <button type="button" class="close" @click="error = null" style="position: absolute;top: 10px;right: 20px;">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa</h5>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateProduct(selectIndex)" style="padding:10px">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Tên</label>
                            <div class="col">
                                <input type="text" class="form-control" v-model="selectProduct.name" name="name"
                                    placeholder="Nhập tên">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="price" class="col-sm-2 col-form-label">Giá</label>
                            <div class="col">
                                <input type="number" class="form-control" v-model="selectProduct.price" name="price"
                                    placeholder="Nhập giá">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col" style="display: flex;justify-content: flex-end;">
                                <button type="button" class="btn btn-secondary" @click="closeModel(2)">Hủy</button>
                                <button type="submit" class="btn btn-danger" style="margin-left:20px">Lưu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
 
<script>

export default {
    // components:{
    //     'Pagination': pagination
    // },
    data() {
        return {
            page: 1,
            product: {
                name: '',
                price: 0
            },
            listProducts: {},
            error: null,
            selectProduct: {
                id: '',
                name: '',
                price: 0
            },
            searchProduct: {
                name: '',
                price: ''
            },
            selectIndex: 0,
            isOpenModel: false
        }
    },
    created() {
        this.getListProducts()
    },
    methods: {
        deleteSearchProduct() {
            this.searchProduct.name = ''
            this.searchProduct.price = ''
            this.getSearchProduct(1, true);
        },
        async createProduct() {
            try {
                this.error = null
                const response = await axios.post('/products/create', {
                    name: this.product.name,
                    price: this.product.price
                })
                // this.listProducts.data.unshift({
                //     ...response.data.product,
                //     isEdit: false
                // })

                // reset giá trị form về ban đầu
                this.product = {
                    name: '',
                    price: 0
                }

                swal({
                    title: 'Thêm thành công',
                    type: "success",
                    showCancelButton: false,
                    showConfirmButton: false,
                    position: 'center',
                    timer: 1500,
                });
                this.deleteSearchProduct()
                setTimeout(function () {
                    $('#createForm').modal('toggle');

                }, 1500);
            } catch (error) {
                this.error = error.response.data
            }
        },
        async getListProducts(page = 1) {
            try {
                const response = await axios.get('/products/get-list?page=' + page)
                this.listProducts = response.data
            } catch (error) {
                this.error = error.response.data
            }
        },
        async getSearchProduct(page = 1, wantBack = false) {
            try {

                const response = await axios.get('/products/search?page=' + page + '&name=' + this.searchProduct.name + '&price=' + this.searchProduct.price)
                this.listProducts = response.data
                if (wantBack) {
                    this.page = 1
                }
            } catch (error) {
                this.error = error.response.data
            }
        },

        closeModel(index = 1) {
            this.error = null
            this.isOpenModel = false
            if (index == 1) {
                $('#createForm').modal('toggle');
            }
            if (index == 2) {
                $('#editForm').modal('toggle');
            }
        },
        onClickCreate() {
            this.error = null
            $('#createForm').modal('show');
        },
        onClickEdit(product, index) {
            this.error = null
            this.selectIndex = index
            this.selectProduct.id = product.id
            this.selectProduct.name = product.name
            this.selectProduct.price = product.price
            this.isOpenModel = true
            console.log(this.selectProduct)
            $('#editForm').modal('show');
        },
        deleteProduct(product, index) {
            swal({
                title: "Nhắc nhở",
                text: "Bạn có chắc muốn xóa sản phẩm " + product.name.toUpperCase() + " không ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "Green",
                confirmButtonText: "Ok",
                closeOnConfirm: false
            }).then(isConfirmed => {
                if (isConfirmed) {
                    try {
                        axios.post('/products/delete/' + product.id)
                        // this.listProducts.data.splice(index, 1)
                        //this.deleteSearchProduct()

                        swal({
                            title: 'Xóa thành công',
                            type: "success",
                            showCancelButton: false,
                            showConfirmButton: false,
                            position: 'center',
                            timer: 1500,
                        });
                        this.deleteSearchProduct()
                    } catch (error) {
                        this.error = error.response.data
                    }
                }
            })
        },
        async updateProduct(index) {
            console.log(index);
            try {
                const response = await axios.post('/products/update/' + this.selectProduct.id, {
                    name: this.selectProduct.name,
                    price: this.selectProduct.price
                })

                // this.listProducts.data[this.selectIndex].name = response.data.product.name
                // this.listProducts.data[this.selectIndex].price = response.data.product.price

                this.isOpenModel = false
                swal({
                    title: 'Sửa thành công',
                    type: "success",
                    showCancelButton: false,
                    showConfirmButton: false,
                    position: 'center',
                    timer: 1500,
                });
                this.deleteSearchProduct()
                setTimeout(function () {
                    $('#editForm').modal('toggle');

                }, 1500);
            } catch (error) {
                this.error = error.response.data
            }
        },
    }
}
</script>

<style>
.pagination {
    display: inline-flex;
}

.pagination>li>button {
    width: 30px;
    height: 30px;
}
</style>
 