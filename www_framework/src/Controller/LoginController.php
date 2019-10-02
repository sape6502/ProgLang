<?php

namespace App\Controller;
use App\Controller\AppController;

class LoginController extends AppController {

    public function index() {

        $this->layout = 'simplebox';

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
        }

    }

}
