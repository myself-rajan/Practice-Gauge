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

Route::get('/', function () {
    return view('/auth/login');
});

//Auth::routes();
Auth::routes(['verify' => true]);

//Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

//Only Authenticated Users will access
Route::group(['middleware' => ['web', 'auth']], function () {
	Route::get('/organization/select', 'CompanyController@selectOrganization')->name('select_organization');
	Route::get('/company/select', 'CompanyController@selectCompany')->name('select_company');
	Route::get('/company/redirect', 'CompanyController@companyRedirect')->name('company_redirect');
	Route::get('company/search', 'CompanyController@searchCompanies')->name('search_company');
	Route::get('organization/search', 'CompanyController@searchOrganization')->name('search_organization');
});

Route::group(['middleware' => ['web', 'auth', 'check_session']], function () {

	Route::get('/home', 'HomeController@index')->name('home');
	Route::post('/get_monthly_collection', 'HomeController@getMonthlyCollection')->name('get_monthly_collection');
	Route::post('/dashboard/get_collection', 'HomeController@getNetCollection')->name('get_net_collection');
	Route::post('/dashboard/get_expenses', 'HomeController@getOperationalExpenses')->name('get_operational_expenses');
	Route::post('/dashboard/get_employee_costs', 'HomeController@getEmployeeCostsExpenses')->name('get_employee_costs_expenses');

	Route::get('/practices', 'PracticesController@availablePractices')->name('available_practices');

	//company
	Route::get('/company/view_all', 'CompanyController@viewAllCompanies')->name('view_all_companies');

	//QuickBooks
	Route::get('settings/qbo', 'QuickbooksController@getQBOIntegrations')->name('qbo_integration');
	Route::get('settings/qbo/connect', 'QuickbooksController@connectQBO')->name('qbo_connect');
	Route::get('settings/qbo/disconnect', 'QuickbooksController@disconnectQBO')->name('qbo_disconnect');
	Route::get('/qbo/syncall', 'QuickbooksController@syncQBO')->name('qbo_integration_sync');
	Route::get('/qbo/callback', 'QuickbooksController@generateToken')->name('qbo_callback');
	Route::get('/qbo/import/account', 'QuickbooksController@importAccounts')->name('import_accounts');
	Route::get('/qbo/import/reports', 'ReportsController@getQBOReports')->name('get_qbo_reports');
	Route::get('/qbo/import/test', 'QuickbooksController@importTest')->name('import_test');


	//Accounts
	Route::match(['get', 'post'], '/accounts', 'AccountsListController@availableAccounts')->name('available_accounts');
	Route::get('/account_mapping', 'AccountsListController@accountMapping')->name('account_mapping');
	Route::post('/save_mapping', 'AccountsListController@saveMapping')->name('save_account_mapping');
	Route::post('/search/accounts', 'AccountsListController@searchAccounts')->name('search_accounts');

	//Practices
	// Route::get('/practices', 'PracticesController@availablePractices')->name('available_practices');
	//Route::get('/self_invite', 'PracticesController@selfInvite')->name('self_invite');
	Route::post('/search/practices', 'PracticesController@searchPractices')->name('search_practices');

	
	//Settings
	Route::get('/settings/account_mapping', 'AccountsListController@accountMapping')->name('settings_account_mapping');
	Route::get('/settings/general', 'SettingsController@generalSettings')->name('general_settings');
	Route::post('/settings/general/save', 'SettingsController@saveSettings')->name('save_general_settings');


	//Accounts
	Route::get('/accounts', 'AccountsListController@availableAccounts')->name('available_accounts');
	Route::get('/account_mapping', 'AccountsListController@accountMapping')->name('account_mapping');
	Route::post('/save_mapping', 'AccountsListController@saveMapping')->name('save_account_mapping');

	//send new login
	Route::post('/sendLoginPwd', 'PracticesController@sendLoginPwd')->name('sendLoginPwd');

	//edit practice details
	
	Route::post('/edit_practice_details', 'PracticesController@editPracticeDetails')->name('edit_practice_details');

	//user Roles
	Route::get('/user_roles', 'UserRoleController@viewRoles')->name('view_roles');
	Route::get('/userRolesFilters', 'UserRoleController@userRolesFilters')->name('userRolesFilters');

	//Common routes
	Route::get('empty', 'Controller@errorRedirect')->name('empty_page');

	//Users
	Route::get('/settings/users', 'UserController@viewUsers')->name('users');
	Route::post('/newUserLogin','UserController@saveUser')->name('newUserLogin');
	Route::post('/editUserList','UserController@editUserList')->name('editUserList');
	Route::post('/updateUser','UserController@updateUser')->name('updateUser');
	Route::post('user/save_profile', 'UserController@saveUserProfile')->name('save_user_profile');
	Route::post('/search/users', 'UserController@searchUsers')->name('search_users');

	//Edit
	Route::get('/editUser','UserController@editUser')->name('editUser');
	Route::post('/edit/userSvd','UserController@editUserSvd')->name('editUserSvd');

	//Dashboard Reports
	Route::get('/dashboard_reports', 'DashboardReportController@dashboardReports')->name('dashboard_reports');
	Route::post('/monthly_collection_report', 'DashboardReportController@monthlyCollectionReport')->name('monthly_collection_report');
	Route::post('/gross_net_collection_report', 'DashboardReportController@grossNetCollectionReport')->name('gross_net_collection_report');
	Route::post('/operational_expenses_report', 'DashboardReportController@operationalExpensesReport')->name('operational_expenses_report');

	//PDF
	Route::match(['get', 'post'], '/generate_pdf', 'DashboardReportController@generatePDF')->name('generate_pdf');
	Route::get('/report_download_pdf/{id}', 'DashboardReportController@reportDownloadPdf')->name('report_download_pdf');
	Route::post('/verify_report', 'DashboardReportController@verifyReport')->name('verify_report');

	//Users
	Route::get('/practices_status', 'DashboardReportController@practicesStatus')->name('practices_status');
	
	Route::post('/update_subscription', 'DashboardReportController@updateSubscription')->name('update_subscription');
});



Route::get('/register/cpa', 'RegisterController@cpaReg')->name('cpa_register');
Route::match(['get', 'post'],'/register/std','RegisterController@signupStd')->name('signup_std');
Route::post('/register/stdSave','RegisterController@saveRegStd')->name('save_reg_std');
Route::get('/register/checkEmailStd','RegisterController@checkEmailStd')->name('check_email_std');
Route::get('/register/checkEmailExistStd','RegisterController@checkEmailExistStd')->name('check_email_exist_std');/*16-05-19*/
Route::get('/register/checkPracticeNameExist','RegisterController@checkPracticeNameExist')->name('check_practice_name_exist_std');/*11-07-19*/

Route::get('/register/sent_success','RegisterController@sentStdSuccess');
Route::get('/register/verify','RegisterController@registerVerify')->name('registerVerify');
Route::get('/registerCPA/verify','RegisterController@registerVerifyCPA')->name('registerVerifyCPA');
Route::post('/register/cpaSave','RegisterController@saveRegCpa')->name('save_reg_cpa');
Route::post('/register/newPassSave','RegisterController@saveNewPass')->name('save_new_password');
Route::get('/register/failure','RegisterController@faliure')->name('faliure');
Route::get('/register/forget_pass','RegisterController@forget_pass')->name('forget_pass');
Route::get('/register/send_forget_pass','RegisterController@send_request_pwd')->name('send_request_pwd');
Route::get('/register_chgNewPwd/password','RegisterController@forgetPwdChange')->name('forgetPwdChange');
Route::post('/register/forgetPwd','RegisterController@saveForgetPwd')->name('saveForgetPwd');


// Practices
Route::get('/self_invite', 'PracticesController@selfInvite')->name('self_invite');
Route::get('/self_inviteNew', 'PracticesController@selfInviteNew')->name('selfInviteNew');
Route::get('/register/verifyStd', 'PracticesController@selfInviteStd')->name('selfInviteStd');
Route::get('/company/register/qbo/syncall', 'QuickbooksController@registerSyncQBO')->name('company_qbo_integration_sync');
Route::get('selfRegister/form','PracticesController@selfInvite')->name('self_invite');
Route::post('switch_login','PracticesController@switchLogin')->name('switch_login');

Route::post('/request/email','PracticesController@requestEmailSend')->name('requestEmailSend');
Route::post('/basicInformationSvd','PracticesController@basicInformationSvd')->name('basicInformationSvd');
Route::post('/basicInformationSvdStd','PracticesController@basicInformationSvdStd')->name('basicInformationSvdStd');

Route::post('/practiceSpecificsSvd','PracticesController@practiceSpecificsSvd')->name('practiceSpecificsSvd');
Route::post('/practiceSpecificsSvdStd','PracticesController@practiceSpecificsSvdStd')->name('practiceSpecificsSvdStd');

Route::post('/confirm_delete', 'PracticesController@confirmDelete')->name('confirm_delete');

Route::post('/email_send/cpa','PracticesController@emailSendCpa')->name('emailSendCpa');

Route::post('/email_send/admin','PracticesController@emailSendAdmin')->name('emailSendAdmin');

// User roles
Route::get('/settings/user_roles','userRolesController@userRoles')->name('user_roles');




//Quickbooks
//Route::get('/connect', 'QuickbooksController@connectQBO')->name('qbo_integration');
Route::get('/qbo/callback', 'QuickbooksController@generateToken')->name('qbo_callback');
Route::get('/qbo/import/account', 'QuickbooksController@importAccounts')->name('import_accounts');
Route::get('/qbo/import/reports', 'ReportsController@getQBOReports')->name('get_qbo_reports');
Route::get('/qbo/import/test', 'QuickbooksController@importTest')->name('import_test');

//Skip
Route::post('/skip','QuickbooksController@skipQbo')->name('skipQbo');
Route::post('/skip/std','QuickbooksController@skipQboStd')->name('skipQboStd');
Route::get('/qbo/skipsuccess', 'PracticesController@qboSkip')->name('skip');