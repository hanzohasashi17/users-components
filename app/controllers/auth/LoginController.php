<?php
namespace App\controllers\auth;
use App\models\User;
use League\Plates\Engine;
use Symfony\Component\HttpFoundation\Request;


class LoginController
{
    private $templates, $request, $user;
    public function __construct(Engine $engine, User $user)
    {
        $this->templates = $engine;
        $this->request = Request::createFromGlobals();
        $this->user = $user;
    }

    public function showLoginPage()
    {
        echo $this->templates->render('login');
    }

    public function login()
    {
        $email = $this->request->request->get('email');
        $password = $this->request->request->get('password');
        if ($this->user->login($email, $password)) {
            header('Location: /');
        }
    }
}
