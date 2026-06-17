<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', static fn () => view('login'));
$routes->get('login', static fn () => view('login'));
$routes->get('auth/login', static fn () => view('login'));
$routes->post('auth/login', static fn () => redirect()->to(site_url('notes')));
$routes->get('dashboard', static fn () => view('dashboard'));
// Notes system routes
$routes->get('notes', 'Notes::index');
$routes->get('notes/view/(:num)', 'Notes::view/$1');
$routes->match(['get','post'], 'notes/add/(:num)', 'Notes::add/$1');
$routes->match(['get','post'], 'notes/add', 'Notes::add');
$routes->post('notes/update/(:num)', 'Notes::update/$1');
$routes->post('notes/delete/(:num)', 'Notes::delete/$1');
$routes->post('notes/create-subject', 'Notes::createForSubject');
