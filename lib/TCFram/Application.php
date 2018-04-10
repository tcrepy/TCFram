<?php

namespace TCFram;

use Symfony\Component\Yaml\Yaml;
use App\MyApp\Modules;

abstract class Application
{
    protected $httpRequest;
    protected $httpResponse;
    protected $name;
    protected $user;
    protected $config;
    protected $url;
    protected $web_url;

    public function __construct()
    {
        $this->httpRequest = new HTTPRequest($this);
        $this->httpResponse = new HTTPResponse($this);
        $this->user = new User($this);
        $this->config = new Config($this);
        $this->name = '';
        $this->url = '';
        $this->web_url = '';
    }

    public function getController()
    {
        $router = new Router;

        $fileName = __DIR__ . '/../../App/' . $this->name() . '/Config/routes.yml';

        $yaml = Yaml::parse(file_get_contents($fileName));
        $routes = $yaml['routes'];
        // On parcourt les routes du fichier XML.
        foreach ($routes as $route) {
            $route = $route['route'];
            $vars = [];

            if (isset($route['vars']) && !empty($route['vars'])) {
                $vars = $route['vars'];
            }

            // On ajoute la route au routeur.
            $router->addRoute(new Route($this->url . $route['url'], $route['module'], $route['action'], $route['name'], $vars));
        }
        try {
            // On récupère la route correspondante à l'URL.
            $matchedRoute = $router->getRoute($this->httpRequest->requestURI());
        } catch (\RuntimeException $e) {
            if ($e->getCode() == Router::NO_ROUTE) {
                // Si aucune route ne correspond, c'est que la page demandée n'existe pas.
                $this->httpResponse->redirect404();
            }
        }
        // On ajoute les variables de l'URL au tableau $_GET.
        $_GET = array_merge($_GET, $matchedRoute->vars());

        $fileName = __DIR__ . '/../../App/' . $this->name() . '/Config/auth.yml';
        $yaml = Yaml::parse(file_get_contents($fileName));
        $authorizations = $yaml['authorizations'];
        if ($this->user()->isAuthenticated() && !in_array($matchedRoute->name(), $authorizations['roles'][$this->user()->role()])) {
            $this->user()->setFlash('Vous n\'avez pas accès à cette page');
            return new Modules\Connexion\ConnexionController($this, 'Connexion', 'index');
        } elseif (Tools::in_array_r($matchedRoute->name(), $authorizations['roles'])) {
            $this->user()->setFlash('Cette page n\'est accessile qu\'aux utilisateurs connectés');
            return new Modules\Connexion\ConnexionController($this, 'Connexion', 'index');
        }

        // On instancie le contrôleur.
        $controllerClass = 'App\\' . $this->name . '\\Modules\\' . $matchedRoute->module() . '\\' . $matchedRoute->module() . 'Controller';
        return new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());
    }

    abstract public function run();

    abstract function addAssets(BackController $controller, array $otherAssets = []);

    public function httpRequest()
    {
        return $this->httpRequest;
    }

    public function httpResponse()
    {
        return $this->httpResponse;
    }

    public function name()
    {
        return $this->name;
    }

    public function config()
    {
        return $this->config;
    }

    public function user()
    {
        return $this->user;
    }

    public function loadEncryptionKeyFromConfig()
    {
        $keyAscii = $this->cryto_key;
        return Key::loadFromAsciiSafeString($keyAscii);
    }

    public function web_url()
    {
        return $this->web_url;
    }

    public function url()
    {
        return $this->url;
    }

    public function cryto_key()
    {
        return $this->cryto_key;
    }
}