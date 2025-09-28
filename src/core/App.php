<?php
class App {
    private $controllerFile = 'DefaultApp';
    private $controllerMethod = 'index';
    private $parameter = [];
    private const DEFAULT_GET = 'GET';
    private const DEFAULT_POST = 'POST';
    private const DEFAULT_PUT = 'PUT';
    private const DEFAULT_DELETE = 'DELETE';
    private $handlers = [];

    public function setDefaultController($controller) {
        $this->controllerFile = $controller;
    }

    public function setDefaultMethod($method) {
        $this->controllerMethod = $method;
    }

    public function get($uri, $callback) {
        $this->setHandler(self::DEFAULT_GET, $uri, $callback);
    }

    public function post($uri, $callback) {
        $this->setHandler(self::DEFAULT_POST, $uri, $callback);
    }

    public function put($uri, $callback) {
        $this->setHandler(self::DEFAULT_PUT, $uri, $callback);
    }

    public function delete($uri, $callback) {
        $this->setHandler(self::DEFAULT_DELETE, $uri, $callback);
    }

    private function setHandler(string $method, string $path, $handler) {
        $this->handlers[$method . $path] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler
        ];
    }
    
    public function run() {
        $url = $this->getUrl();
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $currentUri = '/' . implode('/', $url);
        
        // Sort handlers by path length (descending) to match more specific routes first
        uasort($this->handlers, function($a, $b) {
            return strlen($b['path']) - strlen($a['path']);
        });

        foreach($this->handlers as $handler) {
            if($this->matchRoute($handler['path'], $currentUri) && $requestMethod == $handler['method']) {
                $this->executeHandler($handler, $url);
                return;
            }
        }

        // Default route if no match found
        $this->executeDefault($url);
    }

    private function matchRoute($routePath, $currentUri) {
        // Remove trailing slashes for comparison
        $routePath = rtrim($routePath, '/');
        $currentUri = rtrim($currentUri, '/');
        
        // Exact match
        if($routePath === $currentUri) {
            return true;
        }
        
        // Handle parameters like :id
        $routeSegments = explode('/', trim($routePath, '/'));
        $uriSegments = explode('/', trim($currentUri, '/'));
        
        if(count($routeSegments) !== count($uriSegments)) {
            return false;
        }
        
        for($i = 0; $i < count($routeSegments); $i++) {
            if($routeSegments[$i] !== $uriSegments[$i] && !str_starts_with($routeSegments[$i], ':')) {
                return false;
            }
        }
        
        return true;
    }

    private function executeHandler($handler, $url) {
        if(isset($handler['handler'][0]) && file_exists(__DIR__ . '/../controllers/' . $handler['handler'][0] . '.php')) {
            $this->controllerFile = $handler['handler'][0];
            require_once __DIR__ . '/../controllers/' . $this->controllerFile . '.php';
            $this->controllerFile = new $this->controllerFile();

            if(isset($handler['handler'][1]) && method_exists($this->controllerFile, $handler['handler'][1])) {
                $this->controllerMethod = $handler['handler'][1];
            }

            // Extract parameters from URL for dynamic routes
            $this->parameter = $this->extractParameters($handler['path'], '/' . implode('/', $url));
            
            call_user_func_array([$this->controllerFile, $this->controllerMethod], $this->parameter);
        }
    }

    private function executeDefault($url) {
        require_once __DIR__ . '/../controllers/' . $this->controllerFile . '.php';
        $this->controllerFile = new $this->controllerFile();
        
        if(!empty($url)) {
            $this->parameter = array_values($url);
        }
        
        call_user_func_array([$this->controllerFile, $this->controllerMethod], $this->parameter);
    }

    private function extractParameters($routePath, $currentUri) {
        $routeSegments = explode('/', trim($routePath, '/'));
        $uriSegments = explode('/', trim($currentUri, '/'));
        $parameters = [];

        for($i = 0; $i < count($routeSegments); $i++) {
            if(str_starts_with($routeSegments[$i], ':')) {
                $parameters[] = $uriSegments[$i];
            }
        }

        return $parameters;
    }
    
    private function getUrl() {
        $url = $_SERVER['QUERY_STRING'] ?? '';
        $url = ltrim($url, '/');
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        if(empty($url)) {
            return [];
        }
        
        return explode('/', $url);
    }
}