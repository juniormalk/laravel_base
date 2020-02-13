<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

##Route::get('', function () {
##    return view('welcome');
##});

Auth::routes();

Route::resource('users', 'UserController');
Route::get('users', 'UserController@index')->name('users.index');
Route::post('users/store', 'UserController@store')->name('users.store');
Route::post('users/update/{id}', 'UserController@update')->name('users.update');
Route::any('profile', 'UserController@profile')->name('users.profile');
Route::any('/test', 'PostController@edit');
//Route::get('roles', 'RoleController@index')->name('roles.index');
Route::resource('roles', 'RoleController');
Route::post('roles/store', 'RoleController@store')->name('roles.store');
Route::post('roles/update/{id}', 'RoleController@update')->name('roles.update');
Route::any('upload', 'UploadController@upload')->name('upload');

Route::resource('permissions', 'PermissionController');
Route::post('permissions/store', 'PermissionController@store')->name('permissions.store');
Route::post('permissions/update/{id}', 'PermissionController@update')->name('permissions.update');

Route::get('home', 'HomeController@index')->name('home');
Route::get('app', 'AppController@index')->name('app');
Route::any('test', 'HomeController@test')->name('test');
Route::get('', 'HomeController@index')->name('home');

Route::group(['prefix' => 'g3/companies', 'middleware' => 'auth'], function () {
    Route::get('', 'CompanyController@index')->name('companies');
    Route::get('index', 'CompanyController@index')->name('companies.index');
    Route::any('add/{cnpj?}', 'CompanyController@insert')->name('companies.add');
    Route::post('create', 'CompanyController@create')->name('companies.create');
    Route::any('edit/{id}', 'CompanyController@edit')->name('companies.edit');
    Route::post('update/{id}', 'CompanyController@update')->name('companies.update');
    Route::get('delete/{id}', 'CompanyController@delete')->name('companies.delete');
    Route::post('destroy', 'CompanyController@destroy')->name('companies.destroy');
    Route::get('{id}/employees', 'CompanyController@employees')->name('companies.employees');
    Route::any('{id}/employees/create', 'CompanyController@employeesCreate')->name('companies.employees.create');
    Route::any('{id}/employees/add', 'CompanyController@employeesAdd')->name('companies.employees.add');
    Route::get('{id}/employees/delete/{employees_id}', 'CompanyController@employeesRemove')->name('companies.employees.delete');
    Route::get('{id}/outsourceds', 'CompanyController@outsourceds')->name('companies.outsourceds');
    Route::any('{id}/outsourceds/add', 'CompanyController@outsourcedsAdd')->name('companies.outsourceds.add');
    Route::get('{id}/outsourceds/delete/{employees_id}', 'CompanyController@outsourcedsRemove')->name('companies.outsourceds.delete');
    Route::get('client/{id}', 'CompanyController@client')->name('companies.clients');
    Route::any('client/attach/{id}', 'CompanyController@attach')->name('companies.clients.attach');
    Route::any('attach/', 'CompanyController@attach')->name('companies.attach');
    Route::get('detach/{cp}/{id}', 'CompanyController@detach')->name('companies.detach');
    Route::get('{id}/documents/', 'CompanyController@documents')->name('companies.documents');
    Route::any('{id}/documents/attach', 'CompanyController@documentsAttach')->name('companies.documents.attach');
    Route::any('{cid}/documents/{did}/delivereds/add', 'CompanyController@deliveredsAdd')->name('companies.documents.delivereds.add');
    Route::any('{cid}/documents/delivereds/{did}/edit', 'CompanyController@deliveredsEdit')->name('companies.documents.delivereds.edit');
    Route::post('documents/detach', 'CompanyController@documentsDetach')->name('companies.documents.detach');
    Route::post('companies/documents/filedelete', 'CompanyController@fileDelete')->name('companies.documents.filedelete');
    Route::any('{cid}/documents/{did}/fileupload', 'CompanyController@fileUpload')->name('companies.documents.fileupload');
    
    //branches
    /*
    Route::get('{ow}/branches', 'CompanyController@branches')->name('companies.branches');
    Route::get('{ow}/branches/add/{cnpj?}', 'CompanyController@insert')->name('branches.add');
    Route::get('{ow}/branches/edit/{id}', 'CompanyController@edit')->name('companies.branches.edit');
    Route::get('{ow}/branches/{id}/outsourceds', 'CompanyController@outsourceds')->name('companies.branches.outsourceds');
    Route::any('{ow}/branches/{id}/outsourceds/add', 'CompanyController@outsourcedsAdd')->name('companies.branches.outsourceds.add');
    Route::get('{ow}/branches/clients/{id}', 'CompanyController@client')->name('companies.branches.clients');
    Route::any('{ow}/branches/client/attach/{id}', 'CompanyController@attach')->name('companies.branches.clients.attach');
    Route::any('{ow}/branches/attach/', 'CompanyController@attach')->name('companies.branches.attach');
    Route::get('{ow}/branches/detach/{cp}/{id}', 'CompanyController@detach')->name('companies.branches.detach');
    */    
    
    
});





 