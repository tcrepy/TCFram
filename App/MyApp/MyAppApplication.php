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
        $this->cryto_key = $this->config()->get('crypto_key');
    }

    public function run()
    {
        if (!$this->user()->isAuthenticated() && $this->httpRequest()->method() !== 'POST') {
            $controller = new Modules\Connexion\ConnexionController($this, 'Connexion', 'index');
        } else {
            $controller = $this->getController();
        }
        $this->addAssets($controller);
        $controller->execute();
    }

    /**
     * permet de gerer la liste des js/css sur le projet
     * @param array $otherAssets tableau contenant la liste des assets qu'on souhaite ajouter $otherAssets[]['type'=>'css/js', 'path' => '...']
     */
    public function addAssets(BackController $controller, array $otherAssets = [])
    {
        //liste des assets qu'on souhaite mettre dans le projet
        try {
            $controller->page()->addJs('lib/jQuery.js');
            $controller->page()->addJs('lib/Xbulle/Xbulle.js');
            $controller->page()->addCss('lib/Xbulle/Xbulle.css');
            $controller->page()->addJs('lib/all.js');
            $controller->page()->addCss('lib/all.css');
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