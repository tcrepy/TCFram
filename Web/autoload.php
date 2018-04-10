<?php
const DEFAULT_APP = 'MyApp';

// Si l'application n'est pas valide, on va charger l'application par défaut qui se chargera de générer une erreur 404
if (!isset($_GET['app']) || !file_exists(__DIR__.'/../App/'.$_GET['app'])) $_GET['app'] = DEFAULT_APP;

// On commence par inclure la classe nous permettant d'enregistrer nos autoload
require __DIR__.'/../lib/TCFram/SplClassLoader.php';



// On va ensuite enregistrer les autoloads correspondant à chaque vendor (TCFram, App, Model, etc.)
$TCFramLoader = new SplClassLoader('TCFram', __DIR__.'/../lib');
$TCFramLoader->register();

$appLoader = new SplClassLoader('App', __DIR__.'/..');
$appLoader->register();

$modelLoader = new SplClassLoader('Model', __DIR__.'/../lib/vendors');
$modelLoader->register();

$entityLoader = new SplClassLoader('Entity', __DIR__.'/../lib/vendors');
$entityLoader->register();

// Il ne nous suffit plus qu'à déduire le nom de la classe et à l'instancier
$appClass = 'App\\'.$_GET['app'].'\\'.$_GET['app'].'Application';

//
if (file_exists(__DIR__.'/../vendor/autoload.php'))
    require __DIR__.'/../vendor/autoload.php';

$app = new $appClass;
$app->run();