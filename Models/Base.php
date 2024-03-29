<?php

namespace App\Models;

use App\Database;
use ArrayAccess;
use DB;

class BaseModel implements ArrayAccess {
    static protected string $table = "base";

    private array $data = array();

    public function __construct($data = null) {
        
        if ($data == null) {
            $this->data = array();
        } else {
            if (is_array($data)) {
                $this->data = $data;
            } else if (is_object($data)) {
                $this->data = (array)$data;
            } else {
                throw new \Error("Model created with invalid data");
            }
        }
    }

    public static function where($clause) {

        $args = func_get_args();
        $table = static::$table;

        array_shift($args);
        $query = "SELECT * FROM " . static::$table . " WHERE " . $clause;
        array_unshift($args, $query);

        $class = get_called_class();
        $data = call_user_func_array("DB::query", $args);

        if (count($data) == 0) 
            return [];

        foreach ($data as $res) {
            $objs[] = new $class($res);
        }
        return $objs;
    }

    public static function whereOne($clause) {

        $args = func_get_args();
        $table = static::$table;

        array_shift($args);
        $query = "SELECT * FROM " . static::$table . " WHERE " . $clause;
        array_unshift($args, $query);

        $class = get_called_class();
        $res = call_user_func_array("DB::queryFirstRow", $args);
        if ($res == null)
            return false;
        return new $class($res);
    }

    public static function selectLimit($lmt = 50, $off = 0) {
        $query = "SELECT * FROM " . static::$table . " ORDER BY id DESC LIMIT " . $lmt . " OFFSET " . $off;

        $class = get_called_class();
        $data = call_user_func_array("DB::query", [$query]);
        if (count($data) == 0) 
            return false;

        foreach ($data as $res) {
            $objs[] = new $class($res);
        }
        return $objs;
    }

    public static function whereId($id) {
        return DB::queryFirstRow("SELECT * FROM " . static::$table . " WHERE id = %i", $id);
    }

    // big yikers, but why not xd
    public static function all() {
        return DB::query("SELECT * FROM " . static::$table);
    }

    public static function whereAll() {
        return self::all();
    }

    public function update($clause) {

        $args = func_get_args();
        var_dump($args);
       
        $table = static::$table;
        array_unshift($args, static::$table);
		
		call_user_func_array(array(DB::getMDB(), 'update'), $args);
        
    }

    public function save() {
        if (isset($this->data["id"])) {
            DB::update(static::$table, $this->data, "id=%i", $this->data["id"]);
        } else {
            DB::insert(static::$table, $this->data);
            $this->data["id"] == DB::insertId();
        }
    }

    public function get($key) {
        return $this->data[$key];
    }

    public function set($key, $val) {
        $this->data[$key] = $val;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
}