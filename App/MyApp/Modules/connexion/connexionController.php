<?php

namespace App\MyApp\Modules\Connexion;

use Entity\User;
use TCFram\Application;
use TCFram\BackController;
use TCFram\HTTPRequest;
use \App\MyApp\Modules as Modules;
use TCFram\Tools;

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
        $this->app()->httpResponse()->setPage($this->page())->send();
    }

    public function executeInscriptionForm(HTTPRequest $request)
    {
        $this->app()->httpResponse()->setPage($this->page())->send();
    }

    public function executeAuthentification(HTTPRequest $request)
    {
        if (!($request->postExists('name') && $request->postExists('password'))) {
            $this->app()->httpResponse()->sendJsonErr('Veuillez indiquer un nom d\'utilisateur et un mot de passe');
        }
        try {
            $userManager = $this->managers->getManagerOf('user');
            $users = $userManager->getListInfosByFields(['name' => $request->postData('name')]);
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
                        'id' => $myUser->id(),
                        'name' => $myUser->name(),
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
            $this->app()->user()->unsetSessionDatas(['name', 'role']);
        }

        Tools::redirect($this->app()->web_url() . '/connexion');
    }

    public function executeUpdateForm(HTTPRequest $request)
    {
        try {
            $manager = $this->managers->getManagerOf('user');
            $myUser = $manager->getInfos($this->app()->user()->getSessionDatas('id'));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $this->page()->addVar('myUser', $myUser);
        $this->app()->httpResponse()->setPage($this->page())->send();
    }

    public function executeInscription(HTTPRequest $request)
    {
        if (!($request->postExists('name') && $request->postExists('password') && $request->postExists('password_confirm'))) {
            $this->app()->user()->setFlash('Veuillez remplir tous les champs');
            Tools::redirect($this->app()->web_url() . '/inscription');
        }
        $name = $request->postData('name');
        $password = $request->postData('password');
        $verify = $request->postData('password_confirm');

        if ($password !== $verify) {
            $this->app()->user()->setFlash('Les deux mots de passe entrés ne sont pas les mêmes');
            Tools::redirect($this->app()->web_url() . '/inscription');
        }

        try {
            $userManager = $this->managers->getManagerOf('user');
            $existingUser = $userManager->getInfosByFields(['name' => $name], [], true);
        } catch (\Exception $e) {
            $this->app()->user()->setFlash($e->getMessage());
            Tools::redirect($this->app()->web_url() . '/inscription');
        }

        if ($existingUser !== null) {
            $this->app()->user()->setFlash('Le nom d\'utilisateur existe déjà');
            Tools::redirect($this->app()->web_url() . '/inscription');
        } else {
            try {
                $user = $userManager->insert(['name' => $name, 'password' => password_hash($password, PASSWORD_DEFAULT), 'role' => '0']);
            } catch (\Exception $e) {
                $this->app()->user()->setFlash($e->getMessage());
                Tools::redirect($this->app()->web_url() . '/inscription');
            }
            Tools::redirect($this->app()->web_url() . '/');
        }

    }
}