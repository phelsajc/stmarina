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
                  <li class="breadcrumb-item active">Collection Report</li>
                  </ol>
              </div>
              </div>
          </div>
          </section>
          <section class="content">
              <div class="container-fluid">
                  
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Report</h3>
               
              </div>

              <div class="card-body">

                  <form class="user" enctype="multipart/form-data">
                          <div class="row">
                              <div class="col-sm-2">
                                  <div class="form-group ">
                                      <label>From Date of transaction</label>
                                      <datepicker name="date" required input-class ="dpicker" v-model="filter.fdate" :bootstrap-styling=true></datepicker>
                                  </div>
                              </div>
                              <div class="col-sm-2">
                                  <div class="form-group ">
                                      <label>To Date of transaction</label>
                                      <datepicker name="date" required input-class ="dpicker" v-model="filter.tdate" :bootstrap-styling=true></datepicker>
                                  </div>
                              </div>
                              <div class="col-sm-2">
                                  <div class="form-group ">
                                      <label>Type</label>
                                      <select required class="form-control" v-model="filter.type" >
                                          <option value="PAID">PAID</option>
                                          <option value="UNPAID">UNPAID</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-sm-2">
                                  <div class="form-group">
                                      <label>&nbsp;</label> <br>
                                          <button type="button" @click="showReport()" class="btn btn-info">
                                          Filter
                                          </button>
                                  </div>
                              </div>
                          </div>

              <table class="table">
                  <thead>
                    <tr>
                      <th>Company</th>
                      <th>Payment Type</th>
                      <th>Amount Due</th>
                      <th>Amount Paid</th>
                      <th>Date of transaction</th>
                      <th>Paid Date</th>
                      <th>Date Deposited</th>
                      <th>Date Confirmed</th>
                      <th>Invocie No.</th>
                      <th>Terms</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="e in results">
                      <td>
                        {{ e.company }}
                      </td>
                      <td>
                        {{ e.type }}
                      </td>
                      <td>
                        {{ e.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") }}
                      </td>
                      <td>
                        {{ e.amount!=null?e.amount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","):0 }}
                      </td>
                      <td>
                       <strong> {{ e.dot }}</strong>
                      </td>
                      <td>
                       <strong> {{ e.paid_dt }}</strong>
                      </td>
                      <td>
                       <strong> {{ e.date_deposited }}</strong>
                      </td>
                      <td>
                       <strong> {{ e.date_confirmed }}</strong>
                      </td>
                      <td>
                       <strong> {{ e.invoiceno }}</strong>
                      </td>
                      <td>
                       <strong> {{ e.terms }}</strong>
                      </td>
                      <td>
                        <strong>  {{ e.status }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td>
                       <strong> Total: </strong>
                      </td>
                      <td>
                        <strong>  {{ grand_total }}</strong>
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                    </tr>
                  </tbody>
                </table>
                          
                      </form>
              </div>
              <!-- /.card-body -->
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
      },
      components: {
          Datepicker,
      },
      data() {
          return {                
              filter:{
                  fdate: '',
                  tdate: '',
                  type: 'BOTH'
              },
              results: [],
              grand_total: 0,
          }
      },
      methods:{
          showReport(){         
              axios.post('/api/collectibles-report', {
                  items:this.filter,
              })
              .then(res => {
                  this.grand_total = 0;
                  this.results = res.data
                  console.log(res.data)
                  Toast.fire({
                      icon: 'success',
                      title: 'Saved successfully'
                  });
                  this.results.forEach(e => {
                      this.grand_total += parseFloat(e.amount);
                  })
              })
              .catch(error => console.log(error))
          },
      }
  }
  
</script>

<style>
.pull-right{
  float:right !important;
}
.dpicker{
  background-color: white !important;
}
</style>