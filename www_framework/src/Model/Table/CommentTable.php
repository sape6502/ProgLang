<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class CommentTable extends Table {

    public function initialize() {
        $this->setPrimaryKey('ID_Comment');
    }

}
