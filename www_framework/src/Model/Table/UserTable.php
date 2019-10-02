<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class UserTable extends Table {
    
    public function initialize() {
        $this->setPrimaryKey('ID_User');
    }

}
