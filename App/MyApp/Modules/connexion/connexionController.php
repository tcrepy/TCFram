<?php

namespace App\MyApp\Modules\Connexion;

use TCFram\Application;
use TCFram\BackController;
use TCFram\HTTPRequest;
use \App\MyApp\Modules as Modules;

Class ConnexionController extends BackController
{
    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);
        $assets = [];
        $assets[] = ['type' => 'js', 'path' => 'connexion/connexion.js'];
        try {
            $this->app()->addAssets($this, $assets);
        } catch (\Exception $e) {
            $app->user()->setFlash($e->getMessage());
        }
    }

    public function executeIndex(HTTPRequest $request)
    {
//        $manager = $this->managers->getManagerOf('module');
//        try {
//        } catch (\Exception $e) {
//            echo $e->getMessage();
//        }

        $this->app()->httpResponse()->setPage($this->page())->send();
    }

    public function executeAuthentification(HTTPRequest $request)
    {
        if (!($request->postExists('pseudo') && $request->postExists('password'))) {
            $this->app()->httpResponse()->sendJsonErr('Veuillez indiquer un nom d\'utilisateur et un mot de passe');
        }
        try {
            $userManager = $this->managers->getManagerOf('user');
            $users = $userManager->getListInfosByFields(['pseudo' => $request->postData('pseudo')]);
        } catch (\Exception $e) {
            $this->app()->httpResponse()->sendJsonErr($e->getMessage());
        }
        if ($users === null) {
            $this->app()->httpResponse()->sendJsonErr('Nom d\'utilisateur ou mot de passe incorrect');
        } else {
            foreach ($users as $myUser) {
                if (password_verify($request->postData('password'), $myUser->password())) {
                    $this->app()->user()->setAuthenticated();
                    $this->app()->user()->setSessionDatas([
                        'pseudo' => $myUser->pseudo(),
                        'role' => $myUser->role()
                    ]);
                    $this->app()->httpResponse()->sendJsonConf('Vous êtes connecté');
                }
            }
            $this->app()->httpResponse()->sendJsonErr('Nom d\'utilisateur ou mot de passe incorrect');
        }
    }

    public function executeDeconnexion(HTTPRequest $request)
    {
        if ($this->app()->user()->isAuthenticated()) {
            $this->app()->user()->setAuthenticated(false);
            $this->app()->user()->unsetSessionDatas(['pseudo', 'role']);
        }

        header('Location: '.$this->app()->web_url().'/connexion');
    }
}