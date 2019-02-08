<?php

namespace App\Models;

use App\Database;
use App\Models\BaseModel;

class Orders extends BaseModel {

    public function __construct($data = null) {
        parent::__construct($data);
    }

    static protected $table = "orders";
}