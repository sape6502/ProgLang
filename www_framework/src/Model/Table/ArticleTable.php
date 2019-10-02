<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class ArticleTable extends Table {

    public function initialize() {
        $this->setPrimaryKey('ID_Article');
    }

}
