<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 * Roles Routes
 */
Route::apiResource('/roles', 'Api\Permissions\RoleController')->middleware('auth:api')->except(['store', 'update', 'destroy']);

/*
 * Permissions Routes
 */
Route::apiResource('/permissions', 'Api\Permissions\PermissionController')->middleware('auth:api')->except(['store', 'update', 'destroy']);

/*
 * Authentication and Password Reset Routes
 */
Route::namespace('Api\Auth')->prefix('auth')->group(function() {
    Route::post('/login', 'AuthController@login');
    Route::get('/logout', 'AuthController@logout')->middleware('auth:api');

    Route::prefix('password')->group(function () {
        Route::get('/', 'PasswordResetController@sendPasswordReset');
        Route::put('/reset', 'PasswordResetController@resetPassword');
        Route::get('/{token}', 'PasswordResetController@findPasswordResetToken');
    });

    Route::put('/clients/password', 'PasswordUpdateController@update')->middleware(['auth:api', 'role:client', 'permission:edit-registration']);
    Route::put('/administrators/password', 'PasswordUpdateController@update')->middleware(['auth:api', 'role:administrator', 'permission:edit-registration']);
});

/*
 * Clients Routes
 */
Route::namespace('Api\Users\Client')->prefix('/clients/register')->group(function () {
    Route::post('/', 'ClientController@store');
    Route::get('/activate/resend-confirmation', 'ActivateRegisterController@resendConfirmationEmail');
    Route::put('/activate/{token}', 'ActivateRegisterController@activateRegistration');
});

Route::namespace('Api\Users\Client')->prefix('/clients')->group(function () {
    Route::get('/', 'ClientController@index')->middleware(['auth:api', 'role:super-admin|administrator', 'permission:list-clients']);
    Route::get('/{id}', 'ClientController@show')->middleware(['auth:api', 'role:super-admin|administrator', 'permission:view-client']);
    Route::put('/', 'ClientController@update')->middleware(['auth:api', 'role:client', 'permission:edit-registration']);
});

/*
 * Administrators Routes
 */
Route::namespace('Api\Users\Administrator')->prefix('/administrators/register')->group(function () {
    Route::post('/', 'AdministratorController@store')->middleware(['auth:api', 'role:super-admin']);
    Route::post('/complete-registration', 'CompleteRegistrationController@completeRegistration');
    Route::post('/{token}', 'CompleteRegistrationController@findAdministratorByToken');
});

Route::namespace('Api\Users\Administrator')->prefix('/administrators')->group(function () {
    Route::get('/', 'AdministratorController@index')->middleware(['auth:api', 'role_or_permission:super-admin|list-administrators']);
    Route::get('/{id}', 'AdministratorController@show')->middleware(['auth:api', 'role_or_permission:super-admin|view-administrator']);
    Route::put('/', 'AdministratorController@update')->middleware(['auth:api', 'role:administrator', 'permission:edit-registration']);
});

/*
 * Profile Update (Clients and Administrators) Routes
 */
Route::namespace('Api\Users')->group(function () {
    Route::post('/client/profile-image', 'UploadImageController@update')->middleware(['auth:api', 'role:client', 'permission:edit-registration']);
    Route::post('/administrator/profile-image', 'UploadImageController@update')->middleware(['auth:api', 'role:administrator', 'permission:edit-registration']);
});
