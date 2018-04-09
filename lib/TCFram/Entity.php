<?php

namespace TCFram;

abstract class Entity
{
    protected $erreurs = [],
        $id;

    /**
     * Entity constructor.
     * @param array $donnees
     * @throws \Exception
     */
    public function __construct(array $donnees = [])
    {
        if (!empty($donnees)) {
            try {
                $this->hydrate($donnees);
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    public function isNew()
    {
        return empty($this->id);
    }

    public function erreurs()
    {
        return $this->erreurs;
    }

    public function id()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @param array $donnees
     * @return $this
     * @throws \Exception
     */
    public function hydrate(array $donnees)
    {
        $call = 0;
        foreach ($donnees as $attribut => $valeur) {
            $methode = 'set' . ucfirst($attribut);
            if (is_callable([$this, $methode])) {
                $this->$methode($valeur);
                $call = 1;
            }
        }
        if ($call === 0) {
            throw new \Exception('Pas d\'hydratation possible');
        }
        return $this;
    }
}