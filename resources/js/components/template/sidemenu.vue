<template>
    <aside id="sidebar" class="main-sidebar sidebar-dark-primary elevation-4">
      <router-link to="/all_employee" class="brand-link">
        <img
          :src="img"
          alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3"
          style="opacity: 0.8"
        />
        <span class="brand-text font-weight-light">ST.Marina</span>
      </router-link>
      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img
              :src="img"
              class="img-circle elevation-2"
              alt="User Image"
            />
          </div>
          <div class="info">
            <user-info></user-info>
          </div>
        </div>
        <nav class="mt-2">
          <ul
            class="nav nav-pills nav-sidebar flex-column"
            data-widget="treeview"
            role="menu"
            data-accordion="false"
          >
            <li class="nav-item" :class="{'menu-open':this.$route.name=='company_add'||this.$route.name=='product_add'||this.$route.name=='rproduct_add'||this.$route.name=='rproduct_list'||this.$route.name=='company_list'||this.$route.name=='product_list'}">
            <a href="#" class="nav-link" :class="{'active':this.$route.name=='rproduct_list'||this.$route.name=='company_list'||this.$route.name=='product_list'}">
              <!-- <i class="nav-icon fas fa-edit"></i> -->
              <i class="nav-icon fas fa-folder-plus"></i>
              <p>
                Master Files
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/rproduct_list" ><a href="#" class="nav-link" :class="{'active':this.$route.name=='rproduct_list'||this.$route.name=='rproduct_add'}">
                  <i class="far fa-circle nav-icon"></i>
                  Received
                </a></router-link>
              </li>
              <li class="nav-item">
                <router-link to="/product_list" >  <a href="#" class="nav-link" :class="{'active':this.$route.name=='product_list'||this.$route.name=='product_add'}">
                  <i class="far fa-circle nav-icon"></i>
                    Products
                </a></router-link >
              </li>
              <li class="nav-item">
                <router-link to="/company_list" > <a href="#" class="nav-link" :class="{'active':this.$route.name=='company_list'||this.$route.name=='company_add'}">
                  <i class="far fa-circle nav-icon"></i>
                    Company
                </a></router-link>
              </li>
            </ul>
              
          </li>

            <li class="nav-item" :class="{'menu-open':this.$route.name=='collection_add'||this.$route.name=='collection_list'||this.$route.name=='collection_reports'}">
              <a href="#" class="nav-link" :class="{'active':this.$route.name=='collection_list'||this.$route.name=='collection_reports'}">
                <i class="nav-icon fas fa-random"></i>
                <p>
                  Collections
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/collection_list" class="collapse-item"><a href="#" class="nav-link" :class="{'active':this.$route.name=='collection_list'||this.$route.name=='collection_add'}">
                    <i class="far fa-circle nav-icon"></i>
                    List
                  </a></router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/collection_reports" class="collapse-item"> <a href="#" class="nav-link" :class="{'active':this.$route.name=='collection_reports'}">
                    <i class="far fa-circle nav-icon"></i>
                    Reports
                  </a></router-link>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <router-link to="/collectibles_list" class="collapse-item"><a href="#" class="nav-link" id="usermenu" :class="{'active':this.$route.name=='collectibles_list'}">
                <i class="far fa-circle nav-icon"></i>
                Collectibles
              </a> </router-link>
            </li>
            
            <li class="nav-item" :class="{'menu-open':this.$route.name=='transaction'||this.$route.name=='transaction_list'||this.$route.name=='reports'||this.$route.name=='yearly_report'||this.$route.name=='transaction_report'}">
              <a href="#" class="nav-link" :class="{'active':this.$route.name=='transaction_list'||this.$route.name=='reports'||this.$route.name=='transaction_report'}">
                <i class="nav-icon fas fa-file-invoice"></i>
                <p>
                  Invoice
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/transaction_list" class="collapse-item"> <a href="#" class="nav-link" id="usermenu" :class="{'active':this.$route.name=='transaction_list'||this.$route.name=='transaction'}">
                <i class="far fa-circle nav-icon"></i>
                Transactions
              </a></router-link>
            </li>  

            <li class="nav-item" :class="{'menu-open':this.$route.name=='reports'||this.$route.name=='yearly_report'||this.$route.name=='transaction_report'}">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                   Reports
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link to="/transaction_report" class=""> <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"  :class="{'fa-dot-circle':this.$route.name=='transaction_report'}"></i>     
                      Daily
                    </a></router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/reports" class="">  <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"  :class="{'fa-dot-circle':this.$route.name=='reports'}"></i>     
                      Monthly
                    </a></router-link>
                  </li>
                  <li class="nav-item">
                    <router-link to="/yearly_report" class=""><a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"  :class="{'fa-dot-circle':this.$route.name=='yearly_report'}"></i>     
                      Yearly
                    </a></router-link>
                  </li>
                </ul>
              </li>
              </ul>
            </li>
  
            <li class="nav-item">
              <router-link to="/userslist" class="collapse-item"><a href="#" class="nav-link" id="usermenu" :class="{'active':this.$route.name=='userslist'||this.$route.name=='usersadd'}">
                <i class="far fa-circle nav-icon"></i>
                Users
              </a> </router-link>
            </li>
            
            
            
            <li class="nav-item">
              <router-link to="/stocks" class="collapse-item"> <a href="#" class="nav-link" id="usermenu" :class="{'active':this.$route.name=='stocks'}">
                <i class="far fa-circle nav-icon"></i>
                Stocks                
              </a></router-link>
            </li>
  
            <li class="nav-item">
              <router-link to="/logout" class="nav-link">
                <p>Logout</p>
              </router-link>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
  </template>
  <script>
  export default {
    data() {
      return {
        img: '../../../../backend2/stmarina.jpg'
      }
    },
  }
  </script>
  