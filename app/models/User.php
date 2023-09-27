<?php

namespace App\models;

use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use Delight\Auth\AuthError;
use Delight\Auth\Role;
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

    public function isAdmin()
    {
        if ($this->auth->hasRole(Role::ADMIN)) {
            return 'admin';
        } else {
            return 'user';
        }
    }

    public function getLoggedUserId()
    {
        return $this->auth->getUserId();
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
        } catch (AuthError $e) {
            echo $e->getMessage();
        }
    }

    public function login($email, $password)
    {
        try {
            $this->auth->login($email, $password);
            return true;
        } catch (InvalidEmailException $e) {
            die('Wrong email address');
        } catch (InvalidPasswordException $e) {
            die('Wrong password');
        } catch (EmailNotVerifiedException $e) {
            die('Email not verified');
        } catch (TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function logout()
    {
        $this->auth->logOut();
        return true;
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

    public function editUserSecurity($id, $email, $password, $repeatPassword)
    {
        if ($password === $repeatPassword) {
            try {
                $this->auth->admin()->changePasswordForUserById($id, $password);
                $this->auth->changeEmail($email, function ($selector, $token) {

                });
            } catch (\Delight\Auth\InvalidEmailException $e) {
                die('Invalid email address');
            } catch (\Delight\Auth\UserAlreadyExistsException $e) {
                die('Email address already exists');
            } catch (\Delight\Auth\EmailNotVerifiedException $e) {
                die('Account not verified');
            } catch (\Delight\Auth\NotLoggedInException $e) {
                die('Not logged in');
            } catch (\Delight\Auth\TooManyRequestsException $e) {
                die('Too many requests');
            } catch (\Delight\Auth\UnknownIdException $e) {
                die('Unknown ID');
            } catch (\Delight\Auth\InvalidPasswordException $e) {
                die('Invalid password');
            }
            return true;
        }
    }
    public function editUserStatus($id, $status)
    {
        $update = $this->qf->newUpdate();

        $update->table('users')
            ->cols([
                'status' => $status,
            ])->where('id = :id', ['id' => $id]);

        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
        return true;
    }

    public function updateUserAvatar($id)
    {
        $name = "uploads/images/" . $_FILES["imageUrl"]["name"];
        move_uploaded_file($_FILES["imageUrl"]["tmp_name"], $name);

        $update = $this->qf->newUpdate();

        $update->table('users')
            ->cols([
                'image' => $name,
            ])->where('id = :id', ['id' => $id]);

        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
        return true;
    }

    public function deleteUser($id)
    {
        try {
            $this->auth->admin()->deleteUserById($id);
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown ID');
        }

        if($this->auth->getUserId() === (int)$id) {
            $this->logout();
        }
    }

    public function isLogged()
    {
        return $this->auth->isLoggedIn();
    }
}
