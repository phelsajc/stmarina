
let login = require('./components/auth/login.vue').default;
let register = require('./components/auth/register.vue').default
let forget = require('./components/auth/forget.vue').default
let logout = require('./components/auth/logout.vue').default

//End Aithetication
let home = require('./components/home.vue').default

//Employee Component
let storeEmployee = require('./components/employee/create.vue').default
let all_employee = require('./components/employee/index.vue').default
let editEmployee = require('./components/employee/edit.vue').default


let diagnose_from = require('./components/employee/diagnose.vue').default
let diagnose_from_dctr = require('./components/Forms/diagnose.vue').default


let userslist = require('./components/users/index.vue').default
let usersadd = require('./components/users/create.vue').default

//Products
let product_list = require('./components/products/index.vue').default
let product_add = require('./components/products/create.vue').default
//Products
let company_list = require('./components/company/index.vue').default
let company_add = require('./components/company/create.vue').default
//Transaction
let transaction = require('./components/transactions/create.vue').default
let transaction_list = require('./components/transactions/index.vue').default
//reports
let reports = require('./components/transactions/Reports.vue').default

/*
    path, component & name should be the same inorder to work
*/

export const routes = [
    { path: '/', component: login, name: '/' },
    { path: '/register', component: register, name: 'register' },
    { path: '/forget', component: forget, name: 'logout' },
    { path: '/logout', component: logout, name: 'forget' },
    { path: '/home', component: home, name: 'home' },

    //employee routes
    { path: '/add_employee', component: storeEmployee, name: 'storeEmployee' },
    { path: '/all_employee', component: all_employee, name: 'all_employee' },
    { path: '/edit-employee/:id', component: editEmployee, name: 'edit-employee' },
    { path: '/diagnose-from/:id', component: diagnose_from, name: 'diagnose-from' },
    { path: '/diagnose-from-dctr/:id', component: diagnose_from_dctr, name: 'diagnose-from-dctr' },


    //Users
    { path: '/userslist', component: userslist, name: 'userslist' },
    { path: '/usersadd', component: usersadd, name: 'usersadd' },
    
    //Products
    { path: '/product_list', component: product_list, name: 'product_list' },
    { path: '/product_add', component: product_add, name: 'product_add' },

    //Company
    { path: '/company_list', component: company_list, name: 'company_list' },
    { path: '/company_add', component: company_add, name: 'company_add' },
    
    //Transaction
    { path: '/transaction', component: transaction, name: 'transaction' },
    { path: '/transaction_list', component: transaction_list, name: 'transaction_list' },

    //Reports
    { path: '/reports', component: reports, name: 'reports' },
]


