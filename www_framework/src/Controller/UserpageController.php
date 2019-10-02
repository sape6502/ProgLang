<?php

namespace App\Controller;
use App\Controller\AppController;

class UserpageController extends AppController {

    public function index() {
        $this->set('title', 'Main Page');
    }

    public function login() {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();

            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Your username or password is incorrect.');
        }
    }

}
