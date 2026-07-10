<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('produk', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
    $routes->get('download', 'ProdukController::download');
});

$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
});

$routes->get('keranjang', 'TransaksiController::index', ['filter' => 'auth']);

$routes->get('contact', 'Home::contact', ['filter' => 'role']);

$routes->get('checkout', 'TransaksiController::checkout', ['filter' => 'auth']);
$routes->post('buy', 'TransaksiController::buy', ['filter' => 'auth']);

$routes->get('ajax/destinations', 'TransaksiController::destinations', ['filter' => 'auth']);
$routes->get('ajax/costs', 'TransaksiController::costs', ['filter' => 'auth']);

$routes->get('/diskon', 'DiskonController::index', ['filter' => 'auth']);
$routes->post('/diskon/store', 'DiskonController::store', ['filter' => 'auth']);
$routes->post('/diskon/update/(:num)', 'DiskonController::update/$1', ['filter' => 'auth']);
$routes->get('/diskon/delete/(:num)', 'DiskonController::delete/$1', ['filter' => 'auth']);

$routes->get('/pembelian', 'PembelianController::index', ['filter' => 'auth']);
$routes->get('/pembelian/status/(:num)', 'PembelianController::ubah_status/$1', ['filter' => 'auth']);

$routes->group('api', function ($routes) {
    $routes->resource('discounts', ['controller' => 'Api\DiscountController']);
});