<template>
    <div class="wrapper">
        <navComponent></navComponent>
        <sidemenuComponent></sidemenuComponent>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>&nbsp;</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Free</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">CHARGE SALES INVOICE</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form class="user" @submit.prevent="addInitialdata" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Invoice No:</label>
                                                    <input type="text" class="form-control"
                                                        v-model="transactionDetail.invoiceno">
                                                    <small class="text-danger" v-if="errors.invoiceno">{{
                                                        errors.invoiceno[0] }}</small>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Item</label>
                                                    <products :meds="productList.product" ref="productVal"
                                                        @handle-form-data="clickedShowDetailModal"></products>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Stocks</label>
                                                    <input type="text" class="form-control" id=""
                                                        placeholder="Enter Quantity" readonly v-model="stocks">
                                                    <small class="text-danger" v-if="errors.desc">{{ errors.desc[0]
                                                    }}</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Quantity</label>
                                                    <input type="number" class="form-control" id=""
                                                        placeholder="Enter Quantity" @change="calculateTotal"
                                                        v-model="productList.qty">
                                                    <small class="text-danger" v-if="errors.desc">{{ errors.desc[0]
                                                    }}</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>&nbsp;</label> <br>
                                                    <button :class="[(checkform ? '' : 'd-none')]" type="submit"
                                                        class="btn btn-success">
                                                        Add
                                                    </button>
                                                    <button :class="[(checkform ? '' : 'd-none')]" type="button" @click="save()"
                                                        class="btn btn-info">
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(e, index) in itemList2" :key="e.id" :value="e.id">
                                                <td>
                                                    {{ e.product }}
                                                </td>
                                                <td>
                                                    {{ e.qty }}
                                                </td>
                                                <td>
                                                    <button type="button" @click="removeItem(index)"
                                                        class="btn btn-danger btn-sm" :class="[(checkform ? '' : 'd-none')]">
                                                        Remove </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footerComponent></footerComponent>
    </div>
</template>

<script type="text/javascript">
import Datepicker from 'vuejs-datepicker'
export default {
    created() {
        if (!User.loggedIn()) {
            this.$router.push({ name: '/' })
        }
        this.getCompany();

        let checkId = this.$route.params.id
        if (checkId != 0) {
            this.getId = checkId;
            this.getAddedItems();
            this.isDone = true;
            this.editForm();
            this.isNew = false;
        }
    },
    components: {
        Datepicker
    },
    data() {
        return {
            user_info: {
                patientname: '',
                contactno: '',
                pk_pspatregisters: '',
            },
            errors: {},
            companies: [],
            selectdD: [],
            transactionDetail: {
                terms: 0,
                invoiceno: '',
                dot: '',
                companyid: 0
            },
            productList: {
                product: '',
                description: '',
                qty: 0,
                code: '',
                price: 0,
                total: 0,
            },
            getSelectdeProduct: [],
            getProductDetail: {},
            getId: 0,
            itemList: [],
            itemList2: [],
            grand_total: 0,
            isNew: true,
            isDone: false,
            newTotal: 0,
            stocks: 0
        }
    },
    computed: {
        total() {
            return this.itemList2.reduce((sum, item) => sum + parseFloat(item.total), 0);
        },
        checkform() {
            if (this.isNew) {
                //return this.transactionDetail.dot != '' && this.transactionDetail.invoiceno != '' && this.transactionDetail.companyid != '' ? true : false
                return this.transactionDetail.invoiceno != '' ? true : false
            } else {
                return false;
            }
        }
    },
    methods: {
        getCompany() {
            axios.get('/api/getCompanies')
                .then(({ data }) => (
                    this.companies = data,
                    console.log(this.companies)
                ))
                .catch(console.log('error'))
        },
        addInitialdata() {
            if (this.productList.product != null && this.productList.qty != 0 && this.productList.qty <= this.stocks) {
                this.getSelectdeProduct.qty = Number(this.productList.qty);
                this.$emit('update', this.getSelectdeProduct)
                this.itemList2 = this.$refs.productVal.results3
                this.productList.qty = 0;
                this.$refs.productVal.form.val = '';
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Please check item'
                });
            }
        },
        save() {
            if (this.isNew) {
                axios.post('/api/saveTransactionFree', {
                    items: this.$refs.productVal.results3,
                    head: this.transactionDetail,
                    user: User.user_id(),
                })
                    .then(res => {
                        this.getId = res.data;
                        this.isNew = false
                        Toast.fire({
                            icon: 'success',
                            title: 'Saved successfully'
                        });
                        this.$refs.productVal.results3 = [];
                    })
                    .catch(error => console.log(error))
            } else {
                axios.post('/api/updateTransactionFree', {
                    data: this.transactionDetail,
                    id: this.getId
                })
                    .then(res => {
                        Notification.success()
                    })
                    .catch(error => this.errors = error.response.data.errors)
            }
        },
        clickedShowDetailModal: function (value) {
            this.getSelectdeProduct = value;
            this.productList.product = this.getSelectdeProduct.product
            this.productList.description = this.getSelectdeProduct.description
            this.productList.price = this.getSelectdeProduct.price
            this.productList.id = this.getSelectdeProduct.id
            this.stocks = this.getSelectdeProduct.qty
            console.log(this.getSelectdeProduct.qty)
        },
        calculateTotal() {
            if (this.productList.qty <= this.stocks) {
                this.productList.total = this.productList.price * this.productList.qty;
            }
        },
        getAddedItems() {
            axios.get('/api/getTransactionFree/' + this.getId)
                .then(({ data }) => (
                    this.itemList2 = data,
                    this.itemList2.forEach(e => {
                        this.newTotal += parseFloat(e.total)
                    })
                ))
                .catch()
        },
        removeItem(e) {
            this.itemList2.splice(e, 1);
        },
        editForm() {
            let id = this.$route.params.id
            axios.get('/api/getTransactionHeaderFree/' + id)
                .then(({ data }) => (
                    this.transactionDetail.invoiceno = !Object.keys(data).length === 0 ? this.form.invoiceno : data.invoiceno,
                    this.transactionDetail.dot = !Object.keys(data).length === 0 ? this.form.transactiondate : data.transactiondate,
                    this.transactionDetail.companyid = !Object.keys(data).length === 0 ? this.form.companyid : data.companyid
                ))
                .catch(console.log('error'))
        },
    }
}

</script>

<style>.pull-right {
    float: right !important;
}</style>