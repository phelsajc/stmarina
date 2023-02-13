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
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Products</li>
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
                        <h3 class="card-title">Receive Product</h3>
                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        </div>
                    </div>
                    <div class="card-body">            
                        
                        <form class="user" @submit.prevent="addProduct" enctype="multipart/form-data">
                            
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Product</label>
                                        <!-- <input type="text" class="form-control" id="" placeholder="Enter Product name" v-model="form.name"> -->
                                            <select  class="form-control" v-model="form.pid" @change="selectProduct($event)" >
                                                <option v-for="e in products" :key="e.id" :value="e.id" >{{e.product}}</option>
                                            </select>
                                        <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="text" class="form-control" id="" placeholder="Enter Product Quantity" v-model="form.qty">
                                        <small class="text-danger" v-if="errors.qty">{{ errors.qty[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>UOM</label>
                                        <select  class="form-control" v-model="form.uom" readonly>
                                            <option value="Staff">PCS</option>
                                            <option value="Doctor">KG</option>
                                            <option value="Administrator">LT</option>
                                        </select>
                                        <small class="text-danger" v-if="errors.uom">{{ errors.uom[0] }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Date Purchase</label>
                                        <datepicker name="dor" v-model="form.dor" :bootstrap-styling=true></datepicker>
                                        <small class="text-danger" v-if="errors.dor">{{ errors.dor[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Price </label>
                                        <input type="number" class="form-control" id="" placeholder="Enter Product Price" v-model="form.price" readonly>
                                        <small class="text-danger" v-if="errors.price">{{ errors.price[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Code </label>
                                        <input type="text" class="form-control" id="" placeholder="Enter Product Code" v-model="form.code" readonly>
                                        <small class="text-danger" v-if="errors.code">{{ errors.code[0] }}</small>
                                    </div>
                                </div>
                            </div>                     

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </form>

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
            this.getProducts();
        },
        components: {
            Datepicker
        },
        data() {
            return {
                form: {
                    pid: '',
                    desc: '',
                    qty: '',
                    uom: '',
                    dor:'',
                    code:'',
                },
                user_info:{
                    patientname: '',
                    contactno: '',
                    pk_pspatregisters: '',
                },
                errors:{},
                products:[],
            }
        },

        methods:{
            addProduct(){
                axios.post('/api/rec_products-add',this.form)
                .then(res => {
                    this.$router.push({name: 'rproduct_list'});
                    Notification.success()
                })
                .catch(error => this.errors = error.response.data.errors)
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
                axios.post('/api/rec_saveInitialData',this.form)
                .then(res => {
                    Notification.success()
                    Toast.fire({
                        icon: 'success',
                        title: 'Saved successfully'
                    })
                    this.$router.push({name: 'rec_products'});
                })
                .catch(error => this.errors = error.response.data.errors)
            },
            getPatientInformation(){
                axios.get('/api/getPxInfo/'+this.$route.params.id)
                .then(({data}) => ( this.user_info = data))
                .catch()
            },
            editForm(){                
                let id = this.$route.params.id
                axios.get('/api/rec_getFormDetail/'+id)
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
            getProducts(){
                axios.get('/api/getProducts/')
                .then(({data}) => ( this.products = data))
                .catch()
            },
            selectProduct(e){
                axios.get('/api/products-detail/'+e.target.value)
                    .then(({ data }) => (
                        this.form.uom = data.uom,             
                        this.form.price =  data.price,             
                        this.form.code =  data.code                                          
                ))
                .catch(console.log('error'))
            }
        }
    }
    
</script>

<style>
 .pull-right{
    float:right !important;
 }
</style>