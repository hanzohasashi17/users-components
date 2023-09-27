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
        echo $this->templates->render('allUsers', ['users' => $this->user->getAllUsers(), 'isLogged' => $this->user->isLogged(), 'loggedUserId' => $this->user->getLoggedUserId(), 'isAdmin' => $this->user->isAdmin()]);
    }

    public function showCreateUserPage()
    {
        echo $this->templates->render('createUser', ['isLogged' => $this->user->isLogged()]);
    }

    public function createUser()
    {
        $email = $this->request->request->get('email');
        $password = $this->request->request->get('password');
        $userName = $this->request->request->get('userName');

        if ($this->user->create($email, $password, $userName)) {
            header('Location: /');
        }
    }

    public function showEditUserProfilePage($id)
    {
        echo $this->templates->render('editUserProfile', ['id' => $id, 'isLogged' => $this->user->isLogged()]);
    }

    public function editUserProfile()
    {
        $id = $this->request->request->get('id');
        $userName = $this->request->request->get('userName');
        $address = $this->request->request->get('address');
        $phone = $this->request->request->get('phone');
        $jobTitle = $this->request->request->get('jobTitle');

        if ($this->user->editProfile($id, $userName, $address, $phone, $jobTitle)) {
            header('Location: /');
        }
    }

    public function showEditUserSecurityPage($id)
    {
        echo $this->templates->render('editUserSecurity', ['id' => $id, 'isLogged' => $this->user->isLogged()]);
    }

    public function editUserSecurity()
    {
        $id = $this->request->request->get('id');
        $email = $this->request->request->get('email');
        $password = $this->request->request->get('password');
        $repeatPassword = $this->request->request->get('repeatPassword');

        if ($this->user->editUserSecurity($id, $email, $password, $repeatPassword)) {
            header('Location: /');
        }

    }

    public function showEditUserStatusPage($id)
    {
        echo $this->templates->render('editUserStatus', ['id' => $id, 'isLogged' => $this->user->isLogged()]);
    }

    public function editUserStatus()
    {
        $id = $this->request->request->get('id');
        $status = $this->request->request->get('status');

        if ($this->user->editUserStatus($id, $status)) {
            header('Location: /');
        }
    }

    public function showEditUserMediaPage($id)
    {
        echo $this->templates->render('editUserMedia', ['id' => $id, 'isLogged' => $this->user->isLogged()]);
    }

    public function editUserMedia()
    {
        $id = $this->request->request->get('id');

        if ($this->user->updateUserAvatar($id)) {
            header('Location: /');
        }
    }

    public function deleteUser($id)
    {
        $this->user->deleteUser($id);
        header('Location: /');
    }
}
