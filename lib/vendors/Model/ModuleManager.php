<?php

namespace Model;

use \Entity\Module;
use TCFram\Manager;

class ModuleManager extends Manager
{
    protected static $dbName = 'Module';

    //Vous pouvez ajouter ici vos requêtes custom

    /**
     * @param $id
     * @return array|null
     * @throws \Exception
     */
    public function exemple($id)
    {
        try {
            $sql = 'SELECT * FROM ' . self::$dbName . ' WHERE id = :id';
            $datas = $this->dao->query($sql, ['id' => $id]);
            if (count($datas) > 0) {
                $outDatas = [];
                foreach ($datas as $data) {
                    $outDatas[] = new $this->entity_name($data);
                }
                return $outDatas;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}