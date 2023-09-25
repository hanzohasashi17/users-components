<?php
namespace App\controllers\user;
use App\models\User;
use League\Plates\Engine;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    private $templates, $request, $user;
    public function __construct(Engine $engine, User $user)
    {
        $this->templates = $engine;
        $this->request = Request::createFromGlobals();
        $this->user = $user;
    }

    public function index()
    {
        echo $this->templates->render('allUsers', ['users' => $this->user->getAllUsers()]);
    }

    public function showUserCreatePage()
    {
        echo $this->templates->render('userCreate');
    }

    public function userCreate()
    {
        $email = $this->request->request->get('email');
        $password = $this->request->request->get('password');
        $userName = $this->request->request->get('userName');

        if ($this->user->create($email, $password, $userName)) {
            header('Location: /');
        }
    }

    public function showUserEditPage()
    {
        d($_GET);
        echo $this->templates->render('userEdit');
    }

    public function userEdit()
    {
        $id = $this->request->request->get('id');
        $userName = $this->request->request->get('userName');
        $address = $this->request->request->get('address');
        $phone = $this->request->request->get('phone');
        $jobTitle = $this->request->request->get('jobTitle');

//        if ($this->user->editProfile($id, $userName, $address, $phone, $jobTitle)) {
//            header('Location: /');
//        }
    }

    public function userMedia()
    {
        echo $this->templates->render('userMedia');
    }

    public function userProfile()
    {
        echo $this->templates->render('userProfile');
    }

    public function userSecurity()
    {
        echo $this->templates->render('userSecurity');
    }

    public function userStatus()
    {
        echo $this->templates->render('userStatus');
    }
}
