<?php

namespace App\models;

use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use Delight\Auth\EmailNotVerifiedException;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UserAlreadyExistsException;
use PDO;

class User
{
    private $pdo, $auth, $qf;

    public function __construct(PDO $pdo, QueryFactory $qf)
    {
        $this->pdo = $pdo;
        $this->auth = new Auth($this->pdo);
        $this->qf = $qf;
    }

    public function register($email, $password)
    {
        try {
            return $this->auth->register($email, $password);
        } catch (InvalidEmailException $e) {
            die('Invalid email address');
        } catch (InvalidPasswordException $e) {
            die('Invalid password');
        } catch (UserAlreadyExistsException $e) {
            die('User already exists');
        } catch (TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function login($email, $password)
    {
        try {
            $this->auth->login($email, $password);
        } catch (InvalidEmailException $e) {
            die('Wrong email address');
        } catch (InvalidPasswordException $e) {
            die('Wrong password');
        } catch (EmailNotVerifiedException $e) {
            die('Email not verified');
        } catch (TooManyRequestsException $e) {
            die('Too many requests');
        }
        // return userId
        return $_SESSION['userId'] = $this->getIdByEmail($email);
    }

    public function getIdByEmail($email)
    {
        $select = $this->qf->newSelect();

        $select->cols(['id'])->from('users')->where('email = :email', ['email' => $email]);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function logout()
    {
        unset($_SESSION['userId']);
    }

    public function getAllUsers()
    {
        $select = $this->qf->newSelect();

        $select->cols(['*'])->from('users');

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($email, $password, $userName)
    {
        try {
            $this->auth->admin()->createUser($email, $password, $userName);
            return true;
        } catch (InvalidEmailException $e) {
            die('Invalid email address');
        } catch (InvalidPasswordException $e) {
            die('Invalid password');
        } catch (UserAlreadyExistsException $e) {
            die('User already exists');
        }
    }

    public function editProfile($id, $userName, $address, $phone, $jobTitle)
    {
        $update = $this->qf->newUpdate();

        $update->table('users')
        ->cols([
            'username' => $userName,
            'address' => $address,
            'phone' => $phone,
            'jobTitle' => $jobTitle,
        ])->where('id = :id', ['id' => $id]);

        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
        return true;
    }
}
