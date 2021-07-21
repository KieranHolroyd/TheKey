<?php
namespace App;
require_once './Classes/Util.php';

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
class FileKeyValueEngine implements IKeyValueEngine {
    private $file;
    public function get($key) {
        $data = $this->getData();
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }
        return null;
    }
    public function set($key, $value) {
        // ensure $this->file is defined
        if (!isset($this->file)) {
            $this->file = $this->getFilePath();
        }
        // check if file exists
        if (!file_exists($this->file)) {
            touch($this->file);
        }
        $new_data = $this->getData();
        $file = fopen($this->file, 'w');
        $new_data[$key] = $value;
        fwrite($file, json_encode($new_data));
        fclose($file);
    }
    public function delete($key) {
        $new_data = $this->getData();
        unset($new_data[$key]);

        // save to file
        $file = fopen($this->file, 'w');
        fwrite($file, json_encode($new_data));
        fclose($file);
    }
    public function clear() {
        $file = fopen($this->file, 'w');
        fwrite($file, json_encode(array()));
        fclose($file);
    }
    private function getFilePath($path = null) {
        if($path == null) {
            $path = sys_get_temp_dir();
        }
        $cookie_file_kv = Util::cookieGet("kv_fid");
        $fileid = null;
        // check if id is in session
        if (isset($cookie_file_kv)) {
            $fileid = "keyvalue-".$cookie_file_kv;
        } else {
            $fileid = uniqid('keyvalue-');
            Util::cookieSet('kv_fid', $fileid);
        }
        return $path . '/' . $fileid . '.json';
    }
    private function getData(): array {
        // ensure $this->file is defined
        if (!isset($this->file)) {
            $this->file = $this->getFilePath();
        }
        $data = json_decode(file_get_contents($this->file), true);
        if ($data == null) {
            $data = [];
        }
        return $data;
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
    public static function create($engine = null) {
        if (is_null($engine)) {
            $engine = new VolatileKeyValueEngine();
        }
        return new KeyValue($engine);
    }
}

$kv = KeyValue::create(new FileKeyValueEngine());
$kv->foo = 'bar';
echo $kv->foo;
echo $kv->bar;
unset($kv->foo);
echo $kv->foo;
