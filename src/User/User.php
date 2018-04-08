<?php

namespace User;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;


class User implements AdvancedUserInterface
{
    /**
     * User mail.
     *
     * @var string
     */
    private $username;

    /**
     * User password.
     *
     * @var string
     */
    private $password;

    /**
     * Salt that was originally used to encode the password.
     *
     * @var string
     */
    private $salt;

    /**
     * Role.
     * Values : ROLE_USER or ROLE_ADMIN.
     *
     * @var string
     */
    private $roles;
    
    private $enabled;
    private $accountNonExpired;
    private $credentialsNonExpired;
    private $accountNonLocked;

    private $kitVieRecu;
    private $kitHygieneRecu;
    private $kitRentreeRecu;
    
    public function __construct($username,
                                $password,
                                array $roles = array(),
                                $enabled = true,
                                $userNonExpired = true,
                                $credentialsNonExpired = true,
                                $userNonLocked = true,
                                $kitVieRecu,
                                $kitHygieneRecu,
                                $kitRentreeRecu)
    {
        $this->username = $username;
        $this->password = $password;
        $this->enabled = $enabled;
        $this->accountNonExpired = $userNonExpired;
        $this->credentialsNonExpired = $credentialsNonExpired;
        $this->accountNonLocked = $userNonLocked;
        $this->roles = $roles;
        $this->salt = '';
        $this->kitVieRecu = $kitVieRecu;
        $this->kitHygieneRecu = $kitHygieneRecu;
        $this->kitRentreeRecu = $kitRentreeRecu;
    }

    /**
     * @inheritDoc
     */
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles) {
        $this->roles = $roles;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return $this->accountNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return $this->accountNonLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return $this->credentialsNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
    
    public function isKitVieRecu()
    {
        return $this->kitVieRecu;
    }
    
    public function setKitVieRecu($kitVieRecu)
    {
        $this->kitVieRecu = $kitVieRecu;
        return $this;
    }
    
    public function isKitHygieneRecu()
    {
        return $this->kitHygieneRecu;
    }
    
    public function setKitHygieneRecu($kitHygieneRecu)
    {
        $this->kitHygieneRecu = $kitHygieneRecu;
        return $this;
    }
    
    public function isKitRentreeRecu()
    {
        return $this->kitRentreeRecu;
    }
    
    public function setKitRentreeRecu($kitRentreeRecu)
    {
        $this->kitRentreeRecu = $kitRentreeRecu;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        // Nothing to do here
    }
}
