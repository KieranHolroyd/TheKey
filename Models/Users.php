<?php

namespace App\Models;

use App\Database;
use App\Models\BaseModel;

class User extends BaseModel {

    public function __construct($data = null) {
        parent::__construct($data);
    }

    static protected $table = "users";
}