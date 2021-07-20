<?php
namespace App;

interface IKeyValueEngine {
    public function get($key);
    public function set($key, $value);
    public function delete($key);
    public function clear();
}
class VolatileKeyValueEngine implements IKeyValueEngine {
    private $data = array();
    public function get($key) {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }
    public function set($key, $value) {
        $this->data[$key] = $value;
    }
    public function delete($key) {
        unset($this->data[$key]);
    }
    public function clear() {
        $this->data = array();
    }
}
class KeyValue {

	private IKeyValueEngine $store;

	// constructor
    public function __construct(IKeyValueEngine $engine) {
        $this->store = $engine;
    }
    public function __get($key) {
        return $this->store->get($key);
    }
    public function __set($key, $value) {
        $this->store->set($key, $value);
    }
    public function __unset($key) {
        $this->store->delete($key);
    }
    public function __clear() {
        $this->store->clear();
    }
}