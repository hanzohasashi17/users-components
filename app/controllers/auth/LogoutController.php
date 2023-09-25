<?php
namespace App\controllers\auth;
use App\models\User;

class LogoutController
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function logout()
    {
        $this->user->logout();
        header('Location: /login');
    }
}
