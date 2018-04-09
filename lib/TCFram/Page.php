<?php

namespace TCFram;

class Page extends ApplicationComponent
{
    protected $contentFile;
    protected $vars = [];
    protected $css = [];
    protected $js = [];

    public function addVar($var, $value)
    {
        if (!is_string($var) || is_numeric($var) || empty($var)) {
            throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
        }

        $this->vars[$var] = $value;
    }

    public function getGeneratedPage()
    {
        if (!file_exists($this->contentFile)) {
            throw new \RuntimeException('La vue spécifiée n\'existe pas');
        }

        $user = $this->app->user();
        $project_url = $this->app->config()->get('project_url');
        $css = $this->getCss();
        $js = $this->getJs();


        extract($this->vars);

        ob_start();
        require $this->contentFile;
        $content = ob_get_clean();

        ob_start();
        require __DIR__ . '/../../App/' . $this->app->name() . '/Templates/layout.php';
        return ob_get_clean();
    }

    public function setContentFile($contentFile)
    {
        if (!is_string($contentFile) || empty($contentFile)) {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }

        $this->contentFile = $contentFile;
    }

    public function addCss($file)
    {
        if (!is_string($file) || $file == '') {
            throw new \InvalidArgumentException('Le fichier demandé est invalide');
        }
        $file = 'css/' . $file;
        if (!file_exists($file)) {
            throw new \InvalidArgumentException('Le fichier demandé n\'existe pas');
        }
        $this->css[] = $file;
    }

    public function getCss()
    {
        $css = '';
        if (!empty($this->css)){
            foreach ($this->css as $file) {
                $css .= '<link rel="stylesheet" type="text/css" href="'. $file . "\"/>\n";
            }
        }
        return $css;
    }

    public function addJs($file)
    {
        if (!is_string($file) || $file == '') {
            throw new \InvalidArgumentException('Le fichier demandé est invalide');
        }
        $file = 'js/' . $file;
        if (!file_exists($file)) {
            throw new \InvalidArgumentException('Le fichier demandé n\'existe pas');
        }
        $this->js[] = $file;
    }

    public function getJs()
    {
        $js = '';
        if (!empty($this->js)) {
            foreach ($this->js as $file) {
                $js .= '<script type="text/javascript" src="' . $file . "\"></script>\n";
            }
        }
        return $js;
    }
}