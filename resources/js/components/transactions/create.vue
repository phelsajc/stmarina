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
                        <h3 class="card-title">Initial Data</h3>
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
                                        <label>Total</label>
                                        <input type="text" class="form-control" id="" placeholder="Total" v-model="productList.total">
                                        <small class="text-danger" v-if="errors.desc">{{ errors.desc[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label> <br>
                            <button type="submit" class="btn btn-success">
                            Add
                            </button>
                            <button type="button" @click="save()" class="btn btn-success">
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
                                        <th>Total</th>                        
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
                                           <!-- <button type="button" @click="check()" class="btn btn-danger btn-sm">
                                            check </button> -->
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
            this.getPatientInformation();
            this.editForm();
            this.getCompany();
            this.getAddedItems();
        },
        components: {
            Datepicker
        },
        data() {
            return {
                /* form: {
                    o2_stat: '',
                    pulse_rate: '',
                    rr: '',
                    temp: '',
                    bp: '',
                    weight: '',
                    height: '',
                    chiefcomplaints: '',
                    pspat: this.$route.params.id,
                    user_id: User.user_id()
                }, */
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
            onFileSelected(event){
                let file = event.target.files[0];
                if(file.size > 1048770){
                    Notification.image_Validation()
                    console.log(1)
                }else{
                    let reader = new FileReader();
                    reader.onload = event => {
                        this.form.newphoto =  event.target.result
                    };
                    reader.readAsDataURL(file)
                }

            },
            addInitialdata(){
                this.getSelectdeProduct.price = Number(this.productList.price);        
                this.getSelectdeProduct.total = this.productList.price * this.productList.qty;          
                this.getSelectdeProduct.qty = Number(this.productList.qty);  
                this.$emit('update', this.getSelectdeProduct)  
                this.itemList2  = this.$refs.productVal.results3
                console.log(this.itemList2)
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
            getPatientInformation(){
                axios.get('/api/getPxInfo/'+this.$route.params.id)
                .then(({data}) => ( this.user_info = data))
                .catch()
            },
            editForm(){                
                let id = this.$route.params.id
                axios.get('/api/getFormDetail/'+id)
                    .then(({ data }) => (
                    console.log("l "+data?data:0),
                        this.form.o2_stat = !Object.keys(data).length === 0 ? this.form.o2_stat : data.o2_stat,  
                        this.form.temp = !Object.keys(data).length === 0 ? this.form.temp : data.temp,             
                        this.form.rr = !Object.keys(data).length === 0 ? this.form.rr : data.rr,             
                        this.form.bp = !Object.keys(data).length === 0 ? this.form.bp : data.bp,             
                        this.form.weight = !Object.keys(data).length === 0 ? this.form.weight : data.weight,             
                        this.form.height = !Object.keys(data).length === 0 ? this.form.height : data.height,             
                        this.form.chiefcomplaints = !Object.keys(data).length === 0 ? this.form.chiefcomplaints : data.chiefcomplaints                                 
                ))
                .catch(console.log('error'))
            },
            clickedShowDetailModal: function (value) {
                this.getSelectdeProduct = value;            
                this.productList.product = this.getSelectdeProduct.product
                this.productList.description = this.getSelectdeProduct.description
                //this.productList.qty = this.getSelectdeProduct.qty
                this.productList.price = this.getSelectdeProduct.price
                //this.productList.price = this.getSelectdeProduct.price
                //this.productList.total = this.productList.qty * this.getSelectdeProduct.price
                this.productList.id = this.getSelectdeProduct.id
               /*  this.getSelectdeProduct.price = this.productList.price;        
                this.getSelectdeProduct.total = this.productList.price * this.productList.qty;          
                this.getSelectdeProduct.qty = this.productList.qty;       */    

                console.log(this.productList.qty)
               // this.$emit('update', this.getSelectdeProduct)  

            },
            calculateTotal(){
                this.productList.total = this.productList.price * this.productList.qty;
            },
            getAddedItems(){                
                axios.get('/api/getTransaction/'+this.getId)
                .then(({data}) => ( this.itemList = data))
                .catch()
            },
            removeItem(e){
                this.itemList2.splice(e, 1);
            },
            check(){
                console.log(this.itemList2)
            },
        }
    }
    
</script>

<style>
 .pull-right{
    float:right !important;
 }
</style>