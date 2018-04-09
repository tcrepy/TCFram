<?php

namespace Entity;

use \TCFram\Entity;

class User extends Entity
{
    protected $pseudo,
        $password,
        $role;

    const ADMIN_CODE = 1;

    // SETTERS //

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    // GETTERS //

    public function pseudo()
    {
        return $this->pseudo;
    }

    public function password()
    {
        return $this->password;
    }

    public function role()
    {
        return $this->role;
    }


}