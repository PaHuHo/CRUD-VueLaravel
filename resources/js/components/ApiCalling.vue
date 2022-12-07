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
        <table class="table table-image">
            <thead>
                <tr class="table-primary" style="--bs-table-bg: #3395de; color:white">
                    <th scope="col">ID</th>
                    <th scope="col">Hình ảnh</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody v-if="(listProducts.total > 0)">
                <div style="display:none">{{ (i = (listProducts.current_page - 1) * listProducts.per_page + 1) }}</div>
                <tr v-for="(product, index) in listProducts.data" :key="product.id">
                    <th scope="row" style="vertical-align: middle;">{{ i++ }}</th>
                    <td class="w-25">
                        <img :src="product.image ? product.image : 'product-img/default.png'" width="200" height="200"
                            class="img-fluid img-thumbnail" />
                    </td>
                    <td style="vertical-align: middle;">
                        {{ product.name }}
                    </td>
                    <td style="vertical-align: middle;">
                        ${{ product.price }}
                    </td>
                    <td style="vertical-align: middle;">
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
                <div class="alert alert-danger alert-dismissible" role="alert" v-if="error">
                    <b>{{ error.message }}</b>
                    <ul>
                        <li v-for="(errorName, index) in error.errors" :key="index">
                            {{ errorName[0] }}
                        </li>
                    </ul>
                    <button type="button" class="close" @click="error = null"
                        style="position: absolute;top: 10px;right: 20px;">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa</h5>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="createProduct" style="padding:10px" enctype="multipart/form-data">
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
                            <label for="name" class="col-sm-2 col-form-label">Hình ảnh</label>
                            <div class="col">
                                <input type="file" v-on:change="onImageChange" accept="image/*" id="file"
                                    ref="fileInput" class="form-control">
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
                <div class="alert alert-danger alert-dismissible" role="alert" v-if="error">
                    <b>{{ error.message }}</b>
                    <ul>
                        <li v-for="(errorName, index) in error.errors" :key="index">
                            {{ errorName[0] }}
                        </li>
                    </ul>
                    <button type="button" class="close" @click="error = null"
                        style="position: absolute;top: 10px;right: 20px;">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <img id="photo" :src="selectProduct.image ? selectProduct.image : 'product-img/default.png'"
                            width="200" height="200" style="margin-left: 80px;margin-bottom: 10px;margin-top: 10px;" />
                    </div>
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
                            <label for="name" class="col-sm-2 col-form-label">Hình ảnh</label>
                            <div class="col">
                                <input type="file" v-on:change="onImageChange" id="file" ref="fileInput"
                                    class="form-control" accept="image/*"
                                    onchange="document.getElementById('photo').src = window.URL.createObjectURL(this.files[0])">
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
    data() {
        return {
            image: '',
            page: 1,
            product: {
                name: '',
                price: 0,
            },
            listProducts: {},
            error: null,
            selectProduct: {
                id: '',
                name: '',
                price: 0,
                image: '',
            },
            searchProduct: {
                name: '',
                price: ''
            },
            selectIndex: 0,


        }
    },
    created() {
        this.getListProducts()
    },
    methods: {
        async deleteSearchProduct() {
            this.searchProduct.name = ''
            this.searchProduct.price = ''
            this.getSearchProduct(1, true);
        },
        async createProduct() {
            try {
                this.error = null

                const response = await axios.post('/products/create', {
                    name: this.product.name,
                    price: this.product.price,
                    image: this.image
                })
                await this.deleteSearchProduct()
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
                })
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
            if (index == 1) {
                $('#createForm').modal('toggle');
            }
            if (index == 2) {
                $('#editForm').modal('toggle');
            }
        },
        onClickCreate() {
            this.error = null
            this.reset()
            $('#createForm').modal('show');
        },
        onClickEdit(product, index) {
            this.error = null
            this.reset()
            this.selectIndex = index
            this.selectProduct.id = product.id
            this.selectProduct.name = product.name
            this.selectProduct.price = product.price
            this.selectProduct.image = product.image
            console.log(this.selectProduct)
            $('#editForm').modal('show');
        },
        async deleteProduct(product, index) {
            await swal({
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
                    price: this.selectProduct.price,
                    image: this.image
                },)
                await this.deleteSearchProduct()
                // this.listProducts.data[this.selectIndex].name = response.data.product.name
                // this.listProducts.data[this.selectIndex].price = response.data.product.price

                swal({
                    title: 'Sửa thành công',
                    type: "success",
                    showCancelButton: false,
                    showConfirmButton: false,
                    position: 'center',
                    timer: 1500,
                })
                setTimeout(function () {
                    $('#editForm').modal('toggle');

                }, 1500);
            } catch (error) {
                this.error = error.response.data
            }
        },
        async onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        async createImage(file) {
            let reader = new FileReader();
            reader.onload = (e) => {
                this.image = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        reset() {
            this.image = ''
            const input = this.$refs.fileInput
            input.type = 'text'
            input.type = 'file'
        }
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

.table-primary th {
    text-align: center;
}
</style>
 