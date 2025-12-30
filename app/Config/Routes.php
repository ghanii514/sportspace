<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// app/Config/Routes.php
$routes->get('/', 'Home::index'); 
$routes->get('/lapangan/detail/(:num)', 'Field::detail/$1'); 

$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    
    $routes->get('/', 'Admin::index');
    $routes->get('fields', 'Admin::fields');
    $routes->get('fields/create', 'Admin::createField'); 
    $routes->post('fields/save', 'Admin::saveField'); 
    $routes->get('fields/edit/(:num)', 'Admin::editField/$1');   
    $routes->post('fields/update/(:num)', 'Admin::updateField/$1'); 
    $routes->get('fields/delete/(:num)', 'Admin::deleteField/$1'); 
    
    $routes->get('promos', 'Admin::promos');
    $routes->get('promos/create', 'Admin::createPromo');
    $routes->post('promos/save', 'Admin::savePromo');
    $routes->get('promos/delete/(:num)', 'Admin::deletePromo/$1');
    $routes->get('promos/edit/(:num)', 'Admin::edit/$1');
    $routes->post('promos/update/(:num)', 'Admin::updatePromo/$1');
    
    $routes->get('bookings', 'Admin::bookings');
    $routes->get('bookings/confirm/(:num)', 'Admin::confirmBooking/$1');
    $routes->get('bookings/cancel/(:num)', 'Admin::cancelBooking/$1');
    $routes->get('bookings/delete/(:num)', 'Admin::deleteBooking/$1');
});

$routes->get('/lapangan/hafizh', 'Field::tambah'); 
$routes->post('/lapangan/tambah', to: 'Field::save'); 
$routes->get('kategori', 'Home::filter');


$routes->get('/verify/(:segment)', 'Auth::verify/$1');

$routes->get('profile', 'User::index'); 
$routes->get('profile/edit', 'User::edit');
$routes->post('profile/update', 'User::update');

$routes->get('/search', 'Field::search');

$routes->get('/lapangan/detail/(:num)', 'Field::detail/$1');
$routes->post('booking/process', 'Booking::process');

$routes->get('promo', 'Promo::showPromo');
$routes->get('promo/detail/(:num)', 'Promo::detail/$1');

$routes->get('/riwayat', 'Riwayat::index');

$routes->post('booking/summary', 'Booking::summary');
$routes->post('booking/save', 'Booking::save');

$routes->post('booking/batal/(:any)', 'Booking::batal/$1');
$routes->get('booking/detail/(:any)', 'Booking::detail/$1');
$routes->post('booking/bayar/(:any)', 'Booking::bayar/$1');
$routes->post('booking/check-promo', 'Booking::check_promo');
$routes->get('booking/payment/(:num)', 'Booking::payment/$1');
$routes->post('booking/upload-bukti', 'Booking::uploadBukti');
$routes->get('booking/check-availability', 'Booking::checkAvailability');


$routes->get('/ganti-akun', 'GantiAkun::index');
$routes->get('/ganti-akun/tambah', 'GantiAkun::tambah');
$routes->get('/ganti-akun/switch', 'GantiAkun::switchAction');

$routes->get('tentang', function () {
    return view('pages/tentang');
});
$routes->get('bantuan', function () {
    return view('pages/bantuan');
});

$routes->get('chat', 'Chat::selectOwner');
$routes->get('chat/detail/(:num)', 'Chat::detail/$1');
$routes->post('chat/send', 'Chat::send');


$routes->get('chat/start/(:any)' , 'Chat::startChat/$1');
$routes->get('chat/api/messages/(:num)', 'Chat::apiGetMessages/$1');

$routes->group('owner' , function($routes){
    $routes->get('' , 'Owner::index');
    $routes->get('bookings' , 'Owner::bookings');
    $routes->get('chat' , 'OwnerChatController::index');
    $routes->get('chat/(:any)' , 'OwnerChatController::index/$1');
    $routes->get('api/messages/(:num)', 'OwnerChatController::apiGetMessages/$1');
    $routes->post('chat/send' ,'OwnerChatController::send');

    $routes->get('approve/(:any)' , 'Owner::approve/$1');
    $routes->get('reject/(:any)' , 'Owner::reject/$1');
});


