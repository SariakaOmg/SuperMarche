<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'CaisseController::choix');
$routes->post('/saisieAchat', 'CaisseController::enregistrerChoix');

$routes->get('/achat', 'AchatController::index');
$routes->post('/achat/verifier', 'AchatController::verifyIfCanBuy');
$routes->post('/achat/creer', 'AchatController::createAchat');
$routes->post('/achat/cloturer', 'AchatController::cloturer');
