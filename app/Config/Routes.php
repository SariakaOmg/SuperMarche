<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/choix', 'CaisseController::choix');
$routes->post('/saisieAchat', 'CaisseController::enregistrerChoix');


