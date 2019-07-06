<?php

namespace App {
    class Response implements Extension {
        
        private $data;
        private $data_type;

        public static function InitializeExtension() {
            
        }

        const TYPE_JSON = 0;
        const TYPE_STRING = 1;
        const TYPE_REDIRECT = 2;
        const TYPE_FILE = 3;
        const TYPE_DOWNLOAD = 4;

        

        public static function response(int $code = 200, ?string $message = "", ?Array $headers = []) {

            \http_response_code($code);
            
            $response = new Response();

            if ($message) {
                $response->data_type = TYPE_STRING;
                $response->data = $message;
            }
            
            return $response;
        }

        public function file($file) {
            // TODO: implement this

            throw new Exception("Not implemented");
        }

        public function download($data) {
            // TODO: implement this

            throw new Exception("Not implemented");
        }

        public function redirect($url) {
            $this->data_type = TYPE_REDIRECT;
            $this->data = $url;

            return $this;
        }

        public function headers(Array $headers) {
            foreach ($headers as $key => $value) {
                header('$key: $value');
            }

            return $this;
        }

        public function json(Array $data): self {
            header('Content-Type: application/json');
            $this->data = $data;
            $this->data_type = TYPE_JSON;
            return $this;
        }

        public function send() {
            switch ($this->data_type) {
                case TYPE_JSON:
                    echo json_encode($this->data);
                    break;
                case TYPE_STRING:
                    echo $this->data;
                    break;
                case TYPE_REDIRECT:
                    Util::redirect($this->data, false);
                    break;
                default:
                    throw new Exception("Bad data type");
                    break;
            }
        }
    }
}


namespace {
	use App\Response;

	function response(int $code = 200, ?string $message = "", ?Array $headers = []) {
		return Response::response($code, $message, $headers);
	}
}