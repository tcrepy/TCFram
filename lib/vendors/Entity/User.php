<?php

namespace Entity;

use \TCFram\Entity;

class User extends Entity
{
    protected $name,
        $password,
        $role;

    const ADMIN_CODE = 1;

    // SETTERS //

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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

    public function name()
    {
        return $this->name;
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