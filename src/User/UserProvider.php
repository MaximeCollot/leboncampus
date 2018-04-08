<?php

namespace User;
 
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\DBAL\Connection;
 
class UserProvider implements UserProviderInterface
{
    private $conn;
 
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }
 
    public function loadUserByUsername($mail)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM credentials WHERE mail = ?', array(strtolower($mail)));
        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Le compte "%s" n\'existe pas.', $mail));
        }
 		
        return new User($user['mail'], $user['password'], explode(',', $user['roles']), true, true, true, true, $user['kitVieRecu'], $user['kitHygieneRecu'], $user['kitRentreeRecu']);
    }
 
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
 
        return $this->loadUserByUsername($user->getUsername());
    }
 
    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }

    public function save(User $user) {

        //TODO gérer les erreurs de doublon

        $query = $this->conn->createQueryBuilder();
        $query->insert('credentials')
        ->setValue('mail', '?')
        ->setValue('password', '?')
        ->setValue('roles', '?')
        ->setParameter(0, $user->getUsername())
        ->setParameter(1, $user->getPassword())
        ->setParameter(2, implode(',', $user->getRoles()))
        ;

        $query->execute();
    }

    public function update(User $user) {

        //TODO gérer les erreurs de doublon

        $query = $this->conn->createQueryBuilder();
        $query->update('credentials', 'u')
        ->set('kitVieRecu', $user->isKitVieRecu())
        ->set('kitHygieneRecu', $user->isKitHygieneRecu())
        ->set('kitRentreeRecu', $user->isKitRentreeRecu())
        ->where('u.mail = ?')
        ->setParameter(0, $user->getUsername())
        ;

        $query->execute();
    }
}