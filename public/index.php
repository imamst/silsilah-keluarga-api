<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Imams\SilsilahKeluargaApi\App\Router;
use Imams\SilsilahKeluargaApi\Controller\{
    AnggotaKeluargaController
};

Router::add('GET', '/api/family', AnggotaKeluargaController::class, 'index');
Router::add('POST', '/api/family', AnggotaKeluargaController::class, 'store');
Router::add('GET', '/api/family/([0-9]*)', AnggotaKeluargaController::class, 'show');
Router::add('POST', '/api/family/([0-9]*)', AnggotaKeluargaController::class, 'update');
Router::add('DELETE', '/api/family/([0-9]*)', AnggotaKeluargaController::class, 'destroy');
Router::add('GET', '/api/family/([0-9]*)/children', AnggotaKeluargaController::class, 'showChildrenList');
Router::add('GET', '/api/family/([0-9]*)/grand-children', AnggotaKeluargaController::class, 'showGrandChildrenList');
Router::add('GET', '/api/family/([0-9]*)/grand-children/gender/([a-zA-Z]*)', AnggotaKeluargaController::class, 'showGrandChildrenListByGender');
Router::add('GET', '/api/family/([0-9]*)/aunt', AnggotaKeluargaController::class, 'showAuntList');
Router::add('GET', '/api/family/([0-9]*)/cousin/gender/([a-zA-Z]*)', AnggotaKeluargaController::class, 'showCousinList');

Router::run();
