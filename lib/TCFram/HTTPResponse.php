<?php

namespace TCFram;

class HTTPResponse extends ApplicationComponent
{
    protected $page;

    public function addHeader($header)
    {
        header($header);
    }

    public function redirect($location)
    {
        header('Location: ' . $location);
        exit;
    }

    public function redirect404()
    {
        $this->page = new Page($this->app);
        $this->page->setContentFile(__DIR__ . '/../../Errors/404.html');

        $this->addHeader('HTTP/1.0 404 Not Found');

        $this->send();
    }

    public function send()
    {
        exit($this->page->getGeneratedPage());
    }

    public function sendJson(array $tab, $exit = true)
    {
        echo json_encode($tab);
        if ($exit === true) exit;
    }

    public function sendJsonErr($message = 'Erreur', $exit = true)
    {
        $outDatas = ['etat'=> 'err', 'message'=>$message];
        return $this->sendJson($outDatas, $exit);
    }

    public function sendJsonConf($message = 'Confirmation', $exit = true)
    {
        $outDatas = ['etat'=> 'conf', 'message'=>$message];
        return $this->sendJson($outDatas, $exit);
    }

    public function setPage(Page $page)
    {
        $this->page = $page;
        return $this;
    }

    // Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true
    public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
}