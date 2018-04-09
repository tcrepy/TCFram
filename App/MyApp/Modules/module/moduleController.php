<?php

namespace App\MyApp\Modules\module;

use Entity\Module;
use \TCFram\BackController;
use \TCFram\HTTPRequest;
use \TCFram\Application;

class ModuleController extends BackController
{
    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);
    }

    public function executeIndex(HTTPRequest $request)
    {
        //ajout de variables
        $this->page->addVar('hello', 'Hello World');

        //on demande la page à afficher
        $this->app()->httpResponse()->setPage($this->page())->send();
    }
}