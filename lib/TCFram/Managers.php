<?php
namespace TCFram;

class Managers
{
    protected $api = null;
    protected $dao = null;
    protected $managers = [];

    public function __construct($api, $dao)
    {
        $this->api = $api;
        $this->dao = $dao;
    }

    /**
     * @param $module
     * @throws \InvalidArgumentException
     * @return Manager
     */
    public function getManagerOf($module)
    {
        if (!is_string($module) || empty($module))
        {
            throw new \InvalidArgumentException('Le module spécifié est invalide');
        }

        if (!isset($this->managers[$module]))
        {
            $manager = '\\Model\\'.$module.'Manager';
            $entity = '\\Entity\\'.$module;

            $this->managers[$module] = new $manager($this->dao, $entity);
        }
        return $this->managers[$module];
    }
}