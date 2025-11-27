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
    return view('home');
});

$this->get('auth/login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('auth/login', 'Auth\LoginController@login');
$this->get('auth/logout', 'Auth\LoginController@logout')->name('logout');
$this->post('auth/logout', 'Auth\LoginController@logout');
$this->get('auth/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('auth/register', 'Auth\RegisterController@register');
$this->get('auth/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('auth/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('auth/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('auth/password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', 'SiteController@index')->name('site');
Route::get('/admin', 'Admin\AdminController@index')->name('admin');

Route::resource('admin/events', 'Admin\EventController');
Route::resource('admin/locations', 'Admin\LocationController');
Route::resource('admin/schemes', 'Admin\SchemeController');
Route::resource('admin/orders', 'Admin\OrderController');
Route::resource('admin/participants', 'Admin\ParticipantController');

$this->get('admin/events/{event_id}/price', 'Admin\EventController@prices');
$this->post('admin/events/{event_id}/price-get-sectors', 'Admin\EventController@price_get_sectors');
$this->post('admin/events/{event_id}/price-get-places', 'Admin\EventController@price_get_places');
$this->post('admin/events/{event_id}/price-save-places', 'Admin\EventController@price_save_places');

$this->post('admin/orders/ajax-change-status', 'Admin\OrderController@ajax_change_status');

$this->get('admin/parse_info', 'Admin\SchemeController@parse_info');

$this->post('order-create', 'SiteController@ajax_order_create');
$this->get('success', 'SiteController@index')->name('order-success');
$this->post('success', 'SiteController@index')->name('order-success');
$this->get('order-payment-success', 'SiteController@index')->name('order-payment-success');
$this->get('order-payment-error', 'SiteController@index')->name('order-payment-error');
$this->post('order-payment-check', 'SiteController@yandex_kassa_check')->name('order-payment-check');
$this->post('order-payment-aviso', 'SiteController@yandex_kassa_aviso')->name('order-payment-aviso');

$this->post('event-get-map', 'SiteController@event_get_map')->name('event-get-map');