<?php

namespace TCFram;

class Config extends ApplicationComponent
{
    protected $vars = [];

    public function get($var)
    {
        if (!$this->vars) {
            $path = __DIR__ . '/../../App/' . $this->app->name() . '/Config/app.php';
            include_once($path);
            $this->vars = $vars;
        }

        if (isset($this->vars[$var])) {
            return $this->vars[$var];
        }

        return null;
    }
}