<?php
namespace App\controllers\auth;
use League\Plates\Engine;
use Symfony\Component\HttpFoundation\Request;
use App\models\User;

class RegistrationController
{
    private $templates, $request, $user;
    public function __construct(Engine $engine, User $user)
    {
        $this->templates = $engine;
        $this->request = Request::createFromGlobals();
        $this->user = $user;
    }

    public function showRegisterPage()
    {
        echo $this->templates->render('register');
    }

    public function register()
    {
        $email = $this->request->request->get('email');
        $password = $this->request->request->get('password');
        if ($this->user->register($email, $password)) {
            header('Location: /login');
        }
    }
}
