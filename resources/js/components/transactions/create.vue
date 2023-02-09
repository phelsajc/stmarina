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
                    <li class="breadcrumb-item active">Employee</li>
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
                                    <div class="form-group ">
                                        <label>Date  of Transaction</label>
                                        <datepicker name="birthdate" v-model="transactionDetail.dot" :bootstrap-styling=true></datepicker>
                                        <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Company</label>
                                            <select  class="form-control" v-model="transactionDetail.companyid">
                                                <option v-for="e in companies" :key="e.id" :value="e.id">{{e.company}}</option>
                                            </select>
                                        <small class="text-danger" v-if="errors.companyid">{{ errors.companyid[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Invoice No:</label>
                                        <input type="text" class="form-control" v-model="transactionDetail.invoiceno">
                                        <small class="text-danger" v-if="errors.invoiceno">{{ errors.invoiceno[0] }}</small>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Item</label>
                                        <products :meds="productList.product" ref="productVal" @handle-form-data="clickedShowDetailModal"></products>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="text" class="form-control" id="" placeholder="Enter Price" v-model="productList.price">
                                        <small class="text-danger" v-if="errors.desc">{{ errors.desc[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="text" class="form-control" id="" placeholder="Enter Quantity" @change="calculateTotal" v-model="productList.qty">
                                        <small class="text-danger" v-if="errors.desc">{{ errors.desc[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" id="" placeholder="Total" v-model="productList.total">
                                        <small class="text-danger" v-if="errors.desc">{{ errors.desc[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label> <br>
                            <button :class="[(checkform?'':'d-none')]" type="submit" class="btn btn-success">
                            Add
                            </button>
                            <button :class="[(checkform?'':'d-none')]"  type="button" @click="save()" class="btn btn-info">
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
                                        <th>Unit Price</th>                            
                                        <th>Amount</th>                        
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr  v-for="(e,index) in itemList2" :key="e.id" :value="e.id">
                                        <td>
                                            {{ e.product }}
                                        </td>
                                        <td>
                                            {{ e.qty }}
                                        </td>
                                        <td>
                                            {{ e.price }}
                                        </td>
                                        <td>
                                            {{ e.total }}
                                        </td>
                                        <td>
                                           <button type="button" @click="removeItem(index)" class="btn btn-danger btn-sm">
                                            Remove </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                          
                                        </td>
                                        <td>
                                           
                                        </td>
                                        <td>
                                         
                                        </td>
                                        <td>
                                           <strong>TOTAL: {{ total }}</strong> 
                                        </td>
                                        <td>
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
        created(){
            if(!User.loggedIn()){
                this.$router.push({name: '/'})
            }
            this.getCompany();
            this.getAddedItems();
        },
        components: {
            Datepicker
        },
        data() {
            return {
                user_info:{
                    patientname: '',
                    contactno: '',
                    pk_pspatregisters: '',
                },
                errors:{},
                companies:[],
                selectdD: [],
                transactionDetail:{
                    invoiceno: '',
                    dot: '',
                    companyid: 0
                },
                productList:{
                    product: '',
                    description: '',
                    qty: 0,
                    code: '',
                    price: 0,
                    total: 0,
                },
                getSelectdeProduct: [],
                getProductDetail:{},
                getId:0,
                itemList:[],
                itemList2:[],
                grand_total:0 ,
            }
        },
        computed: {
            total() {
                return this.itemList2.reduce((sum, item) => sum + item.total, 0);
            },
            checkform(){
                return this.transactionDetail.dot!=''&&this.transactionDetail.invoiceno!=''&&this.transactionDetail.companyid!=''? true:false
            }
        },
        methods:{
            getCompany(){
                axios.get('/api/getCompanies')
                    .then(({ data }) => (
                        this.companies = data,
                        console.log(this.companies)              
                ))
                .catch(console.log('error'))
            },
            addInitialdata(){
                if(this.productList.product!=null&&this.productList.qty!=0){
                    this.getSelectdeProduct.price = Number(this.productList.price);        
                    this.getSelectdeProduct.total = this.productList.price * this.productList.qty;          
                    this.getSelectdeProduct.qty = Number(this.productList.qty);  
                    this.$emit('update', this.getSelectdeProduct)  
                    this.itemList2  = this.$refs.productVal.results3
                    this.productList.qty = 0;
                    this.productList.price = 0;
                    this.productList.total = 0;
                    this.$refs.productVal.form.val = '';
                }else{
                    Toast.fire({
                        icon: 'error',
                        title: 'Please check item'
                    });
                }
            },
            save(){
                axios.post('/api/saveTransaction', {
                    items: this.$refs.productVal.results3,
                    head: this.transactionDetail,                    
                    user: User.user_id(),
                })
                .then(res => {
                    console.log(res)
                    this.getId  = res.data;
                    this.getAddedItems();
                    Toast.fire({
                        icon: 'success',
                        title: 'Saved successfully'
                    });
                    this.$refs.productVal.results3 = [];
                })
                .catch(error => console.log(error))
            },
            clickedShowDetailModal: function (value) {
                this.getSelectdeProduct = value;            
                this.productList.product = this.getSelectdeProduct.product
                this.productList.description = this.getSelectdeProduct.description
                this.productList.price = this.getSelectdeProduct.price
                this.productList.id = this.getSelectdeProduct.id
            },
            calculateTotal(){
                this.productList.total = this.productList.price * this.productList.qty;
            },
            getAddedItems(){                
                axios.get('/api/getTransaction/'+this.getId)
                .then(({data}) => ( 
                    this.itemList = data
                ))
                .catch()
            },
            removeItem(e){
                this.itemList2.splice(e, 1);
            },
        }
    }
    
</script>

<style>
 .pull-right{
    float:right !important;
 }
</style>