<?php
/**
 * Created by PhpStorm.
 * User: Danick Takam
 * Date: 18/06/2017
 * Time: 19:49
 */

# src/AppBundle/Entity/Credentials.php

namespace AppBundle\Entity;


class Credentials
{
    protected $login;

    protected $password;

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}