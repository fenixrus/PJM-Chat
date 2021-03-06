<?php
require_once './controllers/baseController.php';

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->__availableActions = array('index', 'login', 'logout');
    }

    public function work()
    {
        parent::work();
        switch ($this->__action)
        {
            case 'login':
                header('Content-type: application/json');
                if ($this->__model->getUserId() > 0)
                    echo ('{"code": "success"}');
                else
                {
                    if ($this->__model->auth($_POST['login'], $_POST['password']))
                    {
                        setcookie("id", $this->__model->getUserId(), time() + 60 * 60 * 24 * 30, '/');
                        setcookie("token", $this->__model->getToken(), time() + 60 * 60 * 24 * 30, '/');
                        echo ('{"code": "success"}');
                    }
                    else
                        echo ('{"code": "nologin"}');
                }
                break;

            case 'logout':

                if ($this->__model->getUserId() > 0)
                {
                    unset($_COOKIE['id']);
                    unset($_COOKIE['token']);
                    setcookie('id', '', time() - 3600, '/');
                    setcookie('token', '', time() - 3600, '/');
                }

                header("Location: /");

                break;

            default:
                //index
                require_once './view/header.php';
                require_once './view/' . $this->__controller.  'View.php';
                require_once './view/footer.php';
                break;

        }
    }
}

