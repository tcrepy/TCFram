<?php

namespace App\MyApp;

use \TCFram\Application;
use TCFram\BackController;

class MyAppApplication extends Application
{

    public function __construct()
    {
        parent::__construct();

        $this->name = 'MyApp';
        $this->url = $this->config()->get('project_url');
        $this->web_url = $this->config()->get('web_url') . $this->url;
    }

    public function run()
    {
        $controller = $this->getController();

        $this->addAssets($controller);
        $controller->execute();
    }

    /**
     * permet de gerer la liste des fichiers js/css sur le projet
     * @param array $otherAssets tableau contenant la liste des assets qu'on souhaite ajouter $otherAssets[]['type'=>'css/js', 'path' => '...']
     */
    public function addAssets(BackController $controller, array $otherAssets = [])
    {
        //liste des assets qu'on souhaite mettre dans le projet
        try {
            $controller->page()->addJs('lib/jQuery.js');
            //ajout de Bootstrap 4
            $controller->page()->addCss('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', true);
            $controller->page()->addJs('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', true);
            $controller->page()->addJs('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', true);

            $controller->page()->addJs('izitoast\dist\js\iziToast.min.js', true);
            $controller->page()->addCss('izitoast\dist\css\iziToast.min.css', true);

            $controller->page()->addJs('lib/all.js');
            $controller->page()->addCss('lib/all.css');


            //assets définis dans les controller de l'app
            foreach ($otherAssets as $asset) {
                switch ($asset['type']) {
                    case 'js':
                        $controller->page()->addJs($asset['path']);
                        break;
                    case 'css':
                        $controller->page()->addCss($asset['path']);
                        break;
                }
            }

        } catch (\Exception $e) {
            $this->user()->setFlash($e->getMessage());
        }
        $controller->execute();
    }
}