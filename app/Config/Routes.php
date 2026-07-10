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
    
    $routes->get('profile', 'Admin::profile');
    $routes->post('profile/update', 'Admin::updateProfile');
    $routes->post('profile/update-photo', 'Admin::updateProfilePicture');
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
    $routes->get('api/chat-list', 'OwnerChatController::apiGetChatList');
    $routes->post('chat/send' ,'OwnerChatController::send');

    $routes->get('approve/(:any)' , 'Owner::approve/$1');
    $routes->get('reject/(:any)' , 'Owner::reject/$1');

});

// --------------------------------------------------------------------
// REST API routes (for Flutter mobile app)
// --------------------------------------------------------------------
$routes->group('api', function ($routes) {
    // CORS preflight
    $routes->options('(:any)', static function () {
        $response = service('response');
        $response->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', '*')
            ->setHeader('Access-Control-Max-Age', '7200');
        return $response;
    });

    // Public
    $routes->get('about', 'Api\AboutApi::index');
    $routes->post('auth/login', 'Api\AuthApi::login');
    $routes->post('auth/register', 'Api\AuthApi::register');
    $routes->post('auth/forgot-password', 'Api\AuthApi::forgotPassword');
    $routes->get('fields', 'Api\FieldApi::index');
    $routes->get('fields/(:num)', 'Api\FieldApi::detail/$1');
    $routes->get('partner-logo/(:any)', 'Api\PartnerLogoApi::serve/$1');
    $routes->get('image/promo/(:any)', 'Api\ImageApi::servePromo/$1');
    $routes->get('image/profile/(:any)', 'Api\ImageApi::serveProfile/$1');
    $routes->get('image/about/(:any)', 'Api\ImageApi::serveAbout/$1');
    $routes->get('image/(:any)', 'Api\ImageApi::serve/$1');
    $routes->get('promos', 'Api\PromoApi::index');
    $routes->get('promos/(:num)', 'Api\PromoApi::detail/$1');
    $routes->get('booking/check-availability', 'Api\BookingApi::checkAvailability');
    $routes->post('booking/check-promo', 'Api\BookingApi::checkPromo');

    // Protected (JWT)
    $routes->get('auth/me', 'Api\AuthApi::me', ['filter' => 'jwt']);
    $routes->put('auth/profile', 'Api\AuthApi::updateProfile', ['filter' => 'jwt']);
    $routes->post('auth/profile/photo', 'Api\AuthApi::updateProfilePhoto', ['filter' => 'jwt']);
    $routes->post('booking', 'Api\BookingApi::create', ['filter' => 'jwt']);
    $routes->get('booking', 'Api\BookingApi::index', ['filter' => 'jwt']);
    $routes->get('booking/(:num)', 'Api\BookingApi::detail/$1', ['filter' => 'jwt']);
    $routes->post('booking/(:num)/upload-bukti', 'Api\BookingApi::uploadBukti/$1', ['filter' => 'jwt']);
    $routes->post('booking/(:num)/cancel', 'Api\BookingApi::cancel/$1', ['filter' => 'jwt']);
    $routes->get('chat/rooms', 'Api\ChatApi::rooms', ['filter' => 'jwt']);
    $routes->get('chat/rooms/(:num)/messages', 'Api\ChatApi::messages/$1', ['filter' => 'jwt']);
    $routes->post('chat/send', 'Api\ChatApi::send', ['filter' => 'jwt']);
});


