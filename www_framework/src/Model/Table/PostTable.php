<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class PostTable extends Table {

    public function initialize() {
        $this->setPrimaryKey('ID_Post');
    }

}
