<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// General section
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::home');
$routes->get('/about', 'Home::about');
$routes->get('/tools', 'Home::tools');

// Blog section
$routes->get('/blog', 'Blog::index');
$routes->get('/blog/(:segment)', 'Blog::post/$1');

// Admin section
$routes->get('/admin', 'Admin::index');
$routes->get('/admin/post/add', 'Admin::post_add');
$routes->post('/admin/post/add', 'Admin::post_add');
$routes->get('/admin/post/edit/(:segment)', 'Admin::post_edit/$1');
$routes->post('/admin/post/edit/(:segment)', 'Admin::post_edit/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
