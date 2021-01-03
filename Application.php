<?php

namespace thecore\phpmvc;

use thecore\phpmvc\UserModel;

use thecore\phpmvc\database\Database;

class Application { 
    public static string $ROOT_DIR;
    public string $layout = 'main';
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $database;
    public ?UserModel $user;
    public View $view;

    public static Application $app;
    public ?Controller $controller = null;
    
    public function __construct($rootPath, array $config) {
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this; // using this approach it can use all the instance in Application e.g Reponse, Request, Router etc.
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->database = new Database($config['database']);
        
        $primaryKeyValue = $this->session->get('user');
        if($primaryKeyValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryKeyValue]);
        } else {
            $this->user = null;
        }
    }

    

    public function getController(): \thecore\phpmvc\Controller {
        return $this->controller;
    }
    public function setController(Controller $controller): void {
        $this->controller = $controller;
    }

    public function login(UserModel $user) {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryKeyValue = $user->{$primaryKey};
        $this->session->set('user', $primaryKeyValue);
        return true;
    }

    public function logout() {
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest() {
        return !self::$app->user;
    }

    public function run() {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }
}

