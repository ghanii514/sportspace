<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// app/Config/Routes.php
$routes->get('/', 'Home::index'); // Halaman utama
$routes->get('/lapangan/detail/(:num)', 'Field::detail/$1'); // Halaman detail lapangan

$routes->get('/lapangan/tambah', 'Field::tambah'); // Rute untuk menampilkan form
$routes->post('/lapangan/tambah', 'Field::save'); // Rute untuk memproses form (menyimpan)

// Rute Autentikasi
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::authLogin');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::saveRegister');
$routes->get('/logout', 'Auth::logout');

$routes->get('/verify/(:segment)', 'Auth::verify/$1');
$routes->get('/profile', 'User::profile');
$routes->post('/profile/update-picture', 'User::updateProfilePicture');

$routes->get('/search', 'Field::search');

$routes->get('/lapangan/detail/(:num)', 'Field::detail/$1');
$routes->post('booking/process', 'Booking::process');

$routes->get('/book','Auth::book');