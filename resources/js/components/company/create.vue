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
                    <li class="breadcrumb-item active">Company</li>
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
                        <h3 class="card-title">Add Company</h3>
                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        </div>
                    </div>
                    <div class="card-body">            
                        
                        <form class="user" @submit.prevent="addEmployee" enctype="multipart/form-data">
                            
                            <div class="row">
                                <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Company</label>
                                    <input type="text" class="form-control" id="" placeholder="Enter Product name" v-model="form.company">
                                    <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                                </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" id="" placeholder="Enter Product Description" v-model="form.desc">
                                    <small class="text-danger" v-if="errors.desc">{{ errors.desc[0] }}</small>
                                </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter ..." v-model="form.address"></textarea>
                                    <small class="text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
                                    </div>
                                </div>
                            </div>                            
                    

                            <!-- <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <h4>Type</h4>
                                        <select  class="form-control" v-model="form.type">
                                            <option value="Staff">Staff</option>
                                            <option value="Doctor">Doctor</option>
                                            <option value="Administrator">Administrator</option>
                                        </select>
                                        <small class="text-danger" v-if="errors.type">{{ errors.type[0] }}</small>
                                    </div>
                                </div>
                            </div> -->                            

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
import AppStorage from '../../Helpers/AppStorage';


    export default {
        created(){
            if(!User.loggedIn()){
                this.$router.push({name: '/'})
            }
            this.getPatientInformation();
            this.editForm();
        },

        data() {
            return {
                form: {
                    company: '',
                    address: '',
                    desc: ''
                },
                user_info:{
                    patientname: '',
                    contactno: '',
                    pk_pspatregisters: '',
                },
                errors:{}
            }
        },

        methods:{
            addEmployee(){
                axios.post('/api/company-add',this.form)
                .then(res => {
                    this.$router.push({name: 'company_list'});
                    Toast.fire({
                        icon: 'success',
                        title: 'Saved successfully'
                    })
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
                axios.post('/api/saveInitialData',this.form)
                .then(res => {
                    Notification.success()
                    Toast.fire({
                        icon: 'success',
                        title: 'Saved successfully'
                    })
                    this.$router.push({name: 'all_employee'});
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
            }
            
        }
    }
    
</script>

<style>
 .pull-right{
    float:right !important;
 }
</style>