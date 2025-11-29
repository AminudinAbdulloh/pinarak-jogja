<?php
class Routes {
    private $controllerFile = 'DefaultApp';
    private $controllerMethod = 'index';
    private $parameter = [];
    private $basePath = '';

    public function __construct() {
        $this->basePath = $this->loadBasePath();
    }

    private function loadBasePath() {
        $configFile = __DIR__ . '/../../config/config.properties';
        
        if (!file_exists($configFile)) {
            return '';
        }
        
        $config = parse_ini_file($configFile, true);
        return isset($config['global']['base_path']) ? $config['global']['base_path'] : '';
    }
    
    public function run() {
        $url = $this->getUrl();
        
        // Skip folder project jika ada
        if($url[0] && $this->basePath && strpos($url[0], $this->basePath) !== false) {
            array_shift($url); // Remove first element
            $url = array_values($url); // Reindex array
        }
        
        // Check controller
        if($url && isset($url[0]) && file_exists(__DIR__ . '/../controllers/' . $url[0] . '.php')) {
            $this->controllerFile = $url[0];
            unset($url[0]);
        }
        
        require_once __DIR__ . '/../controllers/' . $this->controllerFile . '.php';
        $this->controllerFile = new $this->controllerFile();
        
        // Check method
        if(isset($url[1])) {
            if(method_exists($this->controllerFile, $url[1])) {
                $this->controllerMethod = $url[1];
                unset($url[1]);
            }
        }
        
        // Parameters
        if(!empty($url)) {
            $this->parameter = array_values($url);
        }
        
        call_user_func_array([$this->controllerFile, $this->controllerMethod], $this->parameter);
    }
    
    private function getUrl() {
        $url = ltrim($_SERVER['REQUEST_URI'], '/');
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
    }
}