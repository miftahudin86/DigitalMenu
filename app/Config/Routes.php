<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ─── Public ────────────────────────────────────────────────────────────────
$routes->get('/', 'Home::index');
$routes->post('/customer/order', 'Customer::submitOrder');
$routes->get('/dashboard-pelanggan', 'Customer::dashboard');

// ─── Auth ──────────────────────────────────────────────────────────────────
$routes->get('/auth', 'Auth::index');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/auth/create-admin', 'Auth::create_admin');

// ─── Admin (protected by auth filter) ─────────────────────────────────────
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    // Dashboard
    $routes->get('/dashboard', 'Dashboard::index');

    // Category CRUD
    $routes->get('/category', 'Category::index');
    $routes->post('/category/store', 'Category::store');
    $routes->post('/category/update/(:num)', 'Category::update/$1');
    $routes->get('/category/delete/(:num)', 'Category::delete/$1');

    // Menu CRUD
    $routes->get('/menu', 'Menu::index');
    $routes->get('/menu/create', 'Menu::create');
    $routes->post('/menu/store', 'Menu::store');
    $routes->get('/menu/edit/(:num)', 'Menu::edit/$1');
    $routes->post('/menu/update/(:num)', 'Menu::update/$1');
    $routes->get('/menu/delete/(:num)', 'Menu::delete/$1');
    $routes->get('/menu/toggle/(:num)', 'Menu::toggleAvailable/$1');

    // Table & QR Code
    $routes->get('/tables', 'TableAdmin::index');
    $routes->post('/tables/store', 'TableAdmin::store');
    $routes->post('/tables/update/(:num)', 'TableAdmin::update/$1');
    $routes->get('/tables/delete/(:num)', 'TableAdmin::delete/$1');
    $routes->get('/tables/qr/(:num)', 'TableAdmin::qr/$1');
    $routes->get('/tables/print-all', 'TableAdmin::printAll');
});
