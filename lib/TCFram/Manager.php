<?php

namespace TCFram;

use Exception;

/**
 * Class Manager Permet de gérer les requetes faites sur la base de données
 * @package TCFram
 */
abstract class Manager
{
    protected $dao;
    protected $entity_name;
    protected static $dbName;

    /**
     * Manager constructor.
     * @param $dao
     * @param $entity_name
     */
    public function __construct($dao, $entity_name)
    {
        $this->entity_name = $entity_name;
        $this->dao = $dao;
    }

    /**
     * Permet de récupèrer les informations correspondantes à un ID
     *
     * @param $id
     * @return null | Entity
     * @throws Exception
     */
    public function getInfos($id)
    {
        if (!is_numeric($id)) {
            throw new Exception('La valeur envoyée n\'est pas numérique');
        }
        try {
            $datas = $this->dao->row('SELECT * FROM ' . static::$dbName . ' WHERE id = :id', ['id' => $id]);
            if ($datas === null) {
                return null;
            }
            $outDatas = new $this->entity_name($datas);
            return $outDatas;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Permet de récupérer les informations d'un élément à partir d'un tableau de critères
     *
     * @param array $fields Critère de recherche
     * @param array $infos Facultatif permet de limité les informations rendus (par défaut *)
     * @param boolean $nullIfNotExist false par défaut, si vaut true return null si aucun résultat
     * @return Entity|false Retourne le tableau d'info ou false si erreur
     * @throws Exception
     */
    public function getInfosByFields($fields, $infos = array(), $nullIfNotExist = false)
    {
        $queryFields = self::_getQueryFields($fields, 'AND');
        if (!strlen($queryFields)) {
            throw new Exception("Aucune données de filtre");
        }

        //Demande de champ particulier
        $myFields = self::getMyFields($infos);
        if (!(is_string($myFields) && strlen(trim($myFields)))) {
            throw new Exception("Les champs ne sont pas valides");
        }

        $sql = "SELECT " . $myFields . " FROM " . static::$dbName . " WHERE " . $queryFields;
        $datas = $this->dao->query($sql);
        if ($datas === false) {
            throw new Exception('Une erreur a eu lieu lors de la sélection');
        } elseif (count($datas) > 1) {
            throw new Exception('Plusieurs correspondances dans la base');
        } elseif (count($datas) === 0 && $nullIfNotExist === false) {
            throw new Exception('Aucune correspondance dans la base');
        } elseif (count($datas) === 0 && $nullIfNotExist === true) {
            return null;
        }

        try {
            $outDatas = new $this->entity_name($datas[0]);
        } catch (Exception $e) {
            throw $e;
        }

        return $outDatas;
    }

    /**
     * Permet de récupérer les informations d'un élément à partir d'un tableau de critères
     *
     * @param array $fields les valeurs sur lesquelles on souhaite faire notre recherche, avec en clé les noms des colonnes de la table
     * @param array $infos les champs qu'on souhaite récupèrer en retour
     * @param boolean $objet si on souhaite un objet ou un array en retour
     * @return Entity
     * @throws Exception
     */
    public function getListInfosByFields(array $fields, $objet = true, $infos = array())
    {
        $queryFields = self::_getQueryFields($fields, 'AND');
        if (!strlen($queryFields)) {
            throw new Exception("Aucune données de filtre");
        }

        //Demande de champ particulier
        $myFields = self::getMyFields($infos);
        if (!(is_string($myFields) && strlen(trim($myFields)))) {
            throw new Exception("Les champs ne sont pas valides");
        }

        $sql = "SELECT " . $myFields . " FROM " . static::$dbName . " WHERE " . $queryFields;
        $datas = $this->dao->query($sql);

        if ($datas === false) {
            throw new Exception('Une erreur a eu lieu lors de la sélection');
        }

        if ($objet === true) {
            $outDatas = [];
            try {
                foreach ($datas as $data) {
                    $outDatas[] = new $this->entity_name($data);
                }
            } catch (Exception $e) {
                throw $e;
            }
        } else {
            $outDatas = $datas;
        }

        return $outDatas;
    }

    /**
     * Permet de faire un insert
     *
     * @param array $tab les valeurs qu'on veut inserer, avec en clé les noms des champs de la table
     * @return Entity l'objet correspondant à la nouvelle entrée dans la base
     * @throws Exception
     */
    public function insert(array $tab = array())
    {
        if (!(isset($tab) && is_array($tab))) {
            throw new Exception("Le tableau est vide");
        }

        //Création de la chaine sql
        if (count($tab)) {
            $queryFields = self::_getQueryFields($tab, ',');
            if (!strlen($queryFields)) {
                throw new Exception("Aucune données à insérer");
            }
        }

        $sql = "insert into " . static::$dbName . " set " . $queryFields;

        try {
            $query = $this->dao->query($sql);
            $outDatas = new $this->entity_name($tab);
            $outDatas->setId($this->dao->lastInsertId());
        } catch (Exception $e) {
            throw $e;
        }

        return $outDatas;
    }


    /**
     * Permet d'update une entity en base de données
     *
     * @param Entity $entity l'objet qui extend Entity qu'on souhaite mettre à jour
     * @param array $param les paramètres avec lesquels on met à jour l'objet
     * @return Entity l'objet mis à jour
     * @throws Exception
     */
    public function update(Entity $entity, array $param)
    {
        if (!(isset($param) && is_array($param))) {
            throw new Exception("Les paramètres pour la mise à jour doivent être contenus dans un tableau");
        }
        //TODO::test update
        if (count($param)) {
            try {
                $entity->hydrate($param);
            } catch (Exception $e) {
                throw $e;
            }
            $queryFields = self::_getQueryFields($param, ',');
            if (!strlen($queryFields)) {
                throw new Exception("Aucune données de mise à jour");
            }
            $sql = "UPDATE " . static::$dbName . " SET " . $queryFields . " WHERE id = :id";
            $outDatas = $this->dao->query($sql, ['id', $entity->id()]);
            if ($outDatas === 0) {
                throw new Exception('Aucune ligne n\'a été affectée');
            } else {
                return $entity;
            }

        } else {
            throw new Exception('Aucune données de mise à jour');
        }


    }

    //TODO::test delete
    public function delete(Entity $entity)
    {
        $sql = 'DELETE FROM ' . static::$dbName . ' WHERE id = :id';
        return $this->dao->query($sql, ['id', $entity->id()]);
    }

    /**
     * Permet de construire la requète sql
     *
     * @static
     * @param array $tab Tableau associatif des clés/valeurs à exploiter
     * @param string $separator Séparateur entre les paires cle = valeur (par défaut ', ')
     * @return string Chaine de caractères constituée à partir des clés/valeurs et du séparateur
     */
    private static function _getQueryFields($tab, $separator = ',')
    {

        $separator = trim(strtoupper($separator));
        if (!in_array($separator, array(',', 'AND'))) {
            return '';
        }

        $queryFields = '';
        if (is_array($tab) && count($tab)) {
            $i = 0;
            foreach ($tab as $key => $value) {

                if ($key == 'id' && $separator == ',') { // Que pour le cas de l'update
                    continue; // On ignore la clé primaire
                }

                if ($i) {
                    $queryFields .= $separator . ' ';
                }
                $value = "'" . $value . "'";
                $queryFields .= "$key=$value";
                $i++;
            }
        }
        return $queryFields;
    }

    /**
     * Permet d'obtenir les champs formatés pour le select ou * à partir d'un tableau
     * exemple : array('AG_ID','AG_NOM','AG_PRENOM') donnera "AG_ID, AG_NOM, AG_PRENOM"
     * @static
     * @return string Chaine de caractères listant tous les champs
     */
    protected static function getMyFields($fields)
    {
        $myFields = '';
        if (is_array($fields) && count($fields)) {
            foreach ($fields as $field) {
                $field = trim($field);
                if (strlen($myFields)) {
                    $myFields .= ',';
                }
                $myFields .= $field;
            }
        } else {
            $myFields = '*';
        }
        return $myFields;
    }
}