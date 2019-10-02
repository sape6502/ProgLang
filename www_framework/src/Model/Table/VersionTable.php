<?php

namespace App\Model\Table;
use Cake\ORM\Table;

class VersionTable extends Table {

    public function initialize() {
        $this->setPrimaryKey('ID_Version');
    }

}
