<?php
// public/index.php
// Lightweight front controller - place in public/index.php (replace existing if you want)
// Notes:
// - Keeps structure friendly to your existing app/controllers and app/models.
// - URL format: /controller/method/param1/param2
//   Example: /gerant/dashboard  -> app/controllers/GerantController.php::dashboard()
//            /order/form/12    -> app/controllers/OrderController.php::form(12)
// - If controller segment omits "Controller", we append "Controller" automatically.
// - Default controller: GerantController, default method: dashboard (adjust if you prefer index)

session_start();

// load DB config (your config/database.php should set up a $pdo or DB connection helper).
// If you have a different path/name, adjust this require.
if (file_exists(__DIR__ . '/../config/database.php')) {
    require_once __DIR__ . '/../config/database.php';
}

// simple autoloader for app controllers and models and base Controller
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/../app/controllers/{$class}.php",
        __DIR__ . "/../app/models/{$class}.php",
        __DIR__ . "/../app/{$class}.php", // for Controller.php or other helpers
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// parse friendly url (via .htaccess or ?url=... fallback)
$url = '/';
if (isset($_GET['url'])) {
    $url = rtrim($_GET['url'], '/');
} elseif (isset($_SERVER['REQUEST_URI'])) {
    // if using pretty URLs without query param, derive path from REQUEST_URI
    // remove script name or index.php if present
    $base = dirname($_SERVER['SCRIPT_NAME']);
    $req = $_SERVER['REQUEST_URI'];
    if ($base !== '/' && strpos($req, $base) === 0) {
        $req = substr($req, strlen($base));
    }
    $url = trim($req, '/');
}
$segments = $url === '' ? [] : explode('/', filter_var($url, FILTER_SANITIZE_URL));

// defaults
$defaultController = 'GerantController';
$defaultMethod = 'dashboard';

// determine controller name
if (!empty($segments) && $segments[0] !== '') {
    $seg = $segments[0];
    // allow either "gerant" or "GerantController"
    if (stripos($seg, 'controller') === false) {
        $controllerName = ucfirst($seg) . 'Controller';
    } else {
        // make sure class name first letter is upper
        $controllerName = ucfirst($seg);
    }
} else {
    $controllerName = $defaultController;
}

// controller file fallback if not exist -> default
$controllerFile = __DIR__ . "/../app/controllers/{$controllerName}.php";
if (!file_exists($controllerFile)) {
    $controllerName = $defaultController;
    $controllerFile = __DIR__ . "/../app/controllers/{$controllerName}.php";
}

if (!file_exists($controllerFile)) {
    http_response_code(500);
    echo "Controller file not found: {$controllerFile}";
    exit;
}

require_once $controllerFile;

// instantiate controller
if (!class_exists($controllerName)) {
    http_response_code(500);
    echo "Controller class {$controllerName} not found inside file.";
    exit;
}

$controller = new $controllerName();

// determine method
$method = $defaultMethod;
if (isset($segments[1]) && $segments[1] !== '') {
    $method = $segments[1];
} else {
    // if controller defines index, use it
    if (method_exists($controller, 'index')) {
        $method = 'index';
    }
}

// method existence check
if (!method_exists($controller, $method)) {
    http_response_code(404);
    echo "Method '{$method}' not found in controller '{$controllerName}'.";
    exit;
}

// collect params and call
$params = array_slice($segments, 2);
call_user_func_array([$controller, $method], $params);
