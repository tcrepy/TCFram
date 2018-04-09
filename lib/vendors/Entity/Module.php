<?php

//Une entity correspond à une table de la base de données

namespace Entity;

use \TCFram\Entity;

class Module extends Entity
{
    protected $value;

    const VALUE_INVALIDE = 1;

    public function isValid()
    {
        return (!empty($this->value));
    }

    //SETTERS //

    public function setValue($value)
    {
        if (is_string($this->value)) {
            $this->erreurs[] = self::VALUE_INVALIDE;
        }
        return $this->value = $value;
    }

    // GETTERS //

    public function value()
    {
        return $this->value;
    }
}