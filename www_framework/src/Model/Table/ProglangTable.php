<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class ProglangTable extends Table {

    public function initialize() {
        $this->setPrimaryKey('ID_ProgLang');
    }

}
