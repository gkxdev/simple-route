# Route

Düzenli ifade (regex) temelli PHP rotalama kütüphanesi.

## Kullanımı

```php
<?php
require_once 'Route.php';

$route = new Route;

$route->get('/', function() {
    return 'Home!';
});

$route->get('/name/(\w+)', function($name) {
    return 'Name: ' . strip_tags($name);
});

$route->post('/id/(\d+)', function(int $id) {
    return 'ID: ' . $id;
});

$route->run();
```

### Controller Kullanımı
```php
$route->get('/contact', [ContactController::class, 'index']);
$route->post('/contact', [ContactController::class, 'action']);

class ContactController {
    public function __construct() {}

    public function index() {
        // GET
    }

    public function action() {
        // POST
    }
}
```

### Function Kullanımı
```php
$route->get('/contact', 'contact');
$route->post('/contact', 'contactAction');

function contact() {
    // GET
}

function contactAction() {
    // POST
}
```

### Sayfa Bulunamadı

```php
$route->notFound(function() {
    // 404 - Not Found!
});
```

### .htaccess

```
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```