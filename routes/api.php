<?php

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('addEmployee', 'AuthController@refresh');
    
});

Route::get('pdf','PDFController@pdf');
Route::apiResource('/employee','Api\EmployeeController');


Route::match(['get','post'],'/filterEmployee','Api\EmployeeController@filterEmployee');
Route::match(['get','post'],'/patientEmployee','Api\PatientController@filterEmployee');
//Route::match(['get','post'],'/patientEmployee','Api\PatientController@filterEmployee_test');
Route::match(['get','post'],'/check_doctors_detail/{id}','Api\PatientController@check_doctors_detail');

Route::match(['get','post'],'saveInitialData','Api\PatientController@saveInitialData');
Route::match(['get','post'],'searchMedicine','MedicineController@searchMedicine');
Route::match(['get','post'],'searchDiagnostic','MedicineController@searchDiagnostic');
Route::match(['get','post'],'getPxInfo/{pspat}','Api\PatientController@getPxInfo');
Route::match(['get','post'],'getFormDetail/{id}','Api\PatientController@EditInitialData');
Route::match(['get','post'],'upDateHPE','Api\PatientController@upDateHPE');
Route::match(['get','post'],'getDiagnosisInfo/{pspat}','Api\PatientController@getDiagnosisInfo');
Route::match(['get','post'],'addMedicine/{method}/{pspat}/{diagnosis_id}','PrescriptionController@store');
Route::match(['get','post'],'getrequency','PrescriptionController@getrequency');
Route::match(['get','post'],'getPrescribeMedicine/{id}','PrescriptionController@getPrescribeMedicine');
Route::match(['get','post'],'getPrecriptionDetail/{id}','PrescriptionController@getPrecriptionDetail');
Route::match(['get','post'],'updateMedicine/{method}/{diagnosis_id}','PrescriptionController@updateMedicine');
Route::match(['get','post'],'addDiagnostics','PrescriptionController@addDiagnostics');
Route::match(['get','post'],'print_prescription/{id}/{doctor}','PDFController@printPrescription');
Route::match(['get','post'],'getPrescribeLabs/{id}','PrescriptionController@getPrescribeLabs');
Route::match(['get','post'],'destroyLab/{id}','PrescriptionController@destroyLab');
Route::match(['get','post'],'destroyMeds/{id}','PrescriptionController@destroyMeds');


Route::match(['get','post'],'addusers','UserController@registerUser');
Route::match(['get','post'],'listusers','UserController@getAllUsers');

Route::match(['get','post'],'show_frequency/{id}','PrescriptionController@show_frequency');

/**
 * St.Marina
 */

#Products
Route::match(['get','post'],'products-add','ProductController@store');
Route::match(['get','post'],'products-update','ProductController@update');
Route::match(['get','post'],'products','ProductController@index');
Route::match(['get','post'],'products-detail/{id}','ProductController@edit');
Route::match(['get','post'],'products-delete/{id}','ProductController@delete');
Route::match(['get','post'],'searchProduct','ProductController@searchProduct');
Route::match(['get','post'],'getProducts','ProductController@getProducts');


#Company
Route::match(['get','post'],'company-add','CompanyController@store');
Route::match(['get','post'],'company-update','CompanyController@update');
Route::match(['get','post'],'company','CompanyController@index');
Route::match(['get','post'],'company-detail/{id}','CompanyController@edit');
Route::match(['get','post'],'company-delete/{id}','CompanyController@delete');
Route::match(['get','post'],'getCompanies','CompanyController@getCompanies');

#Transaction
Route::match(['get','post'],'saveTransaction','TransactionController@store');
Route::match(['get','post'],'updateTransaction','TransactionController@update');
Route::match(['get','post'],'getTransaction/{id}','TransactionController@getTransaction');
Route::match(['get','post'],'transactions','TransactionController@index');
Route::match(['get','post'],'getTransactionHeader/{id}','TransactionController@getTransactionHeader');
Route::match(['get','post'],'report','TransactionController@report');

#ReceivedProducts
Route::match(['get','post'],'rec_products-add','ReceivedProductController@store');
Route::match(['get','post'],'rec_products-update','ReceivedProductController@update');
Route::match(['get','post'],'rec_products','ReceivedProductController@index');
Route::match(['get','post'],'rec_products-detail/{id}','ReceivedProductController@edit');
Route::match(['get','post'],'rec_products-delete/{id}','ReceivedProductController@delete');
Route::match(['get','post'],'rec_searchProduct','ReceivedProductController@searchProduct');












