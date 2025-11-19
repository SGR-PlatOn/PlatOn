<?php
/**
 * POINT D'ENTRÉE UNIQUE - Le routeur détermine quel code exécuter.
 */

// 1. Démarrer un mécanisme d'autoloading (recommandé pour ne pas utiliser require_once)
// Si vous n'utilisez pas d'autoloading, vos require_once sont nécessaires, mais incomplets :

require_once __DIR__.'/../app/controllers/GerantController.php';
require_once __DIR__.'/../app/controllers/OrderController.php';
require_once __DIR__.'/../app/controllers/TableController.php';

// --- 2. ANALYSE DE L'URL (ROUTAGE) ---
// Récupérer l'URL demandée. Exemple : /order/add ou /table/map
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
$segments = explode('/', $uri);

// On détermine le contrôleur (ex: 'table') et l'action (ex: 'map')
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'TableController';
$actionName = !empty($segments[1]) ? $segments[1] : 'map'; 
// Note : Le nom du fichier DOIT correspondre (ex: TableController.php)

// --- 3. EXECUTION (DISPATCHING) ---

// Construction du nom complet de la classe (sans namespace ici, mais le nom du fichier)
$controllerClass = $controllerName; 
$controllerFile = __DIR__ . '/../app/controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    // Si vous utilisez l'autoloading, cette étape n'est pas nécessaire
    // require_once $controllerFile; 

    // Instancier le bon contrôleur (ex: new TableController())
    $controller = new $controllerClass();

    // Appeler la bonne méthode (ex: $controller->map())
    if (method_exists($controller, $actionName)) {
        // Exécuter la méthode
        $controller->$actionName();
    } else {
        // Gérer les actions non trouvées (404)
        header("HTTP/1.0 404 Not Found");
        echo "Action '$actionName' non trouvée.";
    }
} else {
    // Gérer les contrôleurs non trouvés (404)
    header("HTTP/1.0 404 Not Found");
    echo "Contrôleur '$controllerClass' non trouvé.";
}
