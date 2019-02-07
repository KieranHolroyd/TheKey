<?php

namespace App\Models;

use App\Database;

class BaseModel extends Database {
    protected $table = "";

    private function __construct() {
        $this->table = get_class($this);
    }
    

    public function where() {
        
    }

    public function whereOne() {

    }

    public function update() {

    }



    public function save() {

    }
}