<?php

namespace TCFram;

use Symfony\Component\Yaml\Yaml;

class Config extends ApplicationComponent
{
    protected $vars = [];

    public function get($var)
    {
        if (!$this->vars) {
            $path = __DIR__ . '/../../App/' . $this->app->name() . '/Config/config.yml';
            $yaml = Yaml::parse(file_get_contents($path));
            $this->vars = $yaml;
            $path = __DIR__ . '/../../App/' . $this->app->name() . '/Config/database.yml';
            $yaml = Yaml::parse(file_get_contents($path));
            $this->vars['dbInfos'] = $yaml['dbInfos'];
        }

        if (isset($this->vars[$var])) {
            return $this->vars[$var];
        }

        return null;
    }
}