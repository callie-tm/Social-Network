<?php
// Emergency fix script - run via PHP CLI or browser
// This script fixes Nginx config and restarts services

$sudoPass = '45464748';

$results = [];

// 1. Update Nginx config
$nginxConfig = 'server {
    listen 80 default_server;
    listen [::]:80 default_server;

    server_name _;

    root /home/mai;

    index index.html index.htm index.php;

    location ~ \\.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    location ~ /\\.ht {
        deny all;
    }

    location / {
        try_files \\$uri \\$uri/ =404;
    }
}
';

// Write nginx config
$cmd = "echo '$sudoPass' | sudo -S bash -c 'cat > /etc/nginx/sites-available/default << \"ENDCONF\"\n{$nginxConfig}\nENDCONF\n'";
$output = shell_exec($cmd . ' 2>&1');
$results[] = "Nginx config: " . ($output ?: 'OK');

// Set permissions
$output = shell_exec("echo '$sudoPass' | sudo -S chmod 755 /home/mai 2>&1");
$results[] = "Permissions /home/mai: " . ($output ?: 'OK');

$output = shell_exec("echo '$sudoPass' | sudo -S chmod -R 755 /home/mai/socialnet 2>&1");
$results[] = "Permissions /home/mai/socialnet: " . ($output ?: 'OK');

// Restart services
$output = shell_exec("echo '$sudoPass' | sudo -S systemctl restart php8.3-fpm 2>&1");
$results[] = "PHP-FPM restart: " . ($output ?: 'OK');

$output = shell_exec("echo '$sudoPass' | sudo -S systemctl restart nginx 2>&1");
$results[] = "Nginx restart: " . ($output ?: 'OK');

$output = shell_exec("echo '$sudoPass' | sudo -S systemctl restart mysql 2>&1");
$results[] = "MySQL restart: " . ($output ?: 'OK');

// Check status
$output = shell_exec("echo '$sudoPass' | sudo -S systemctl is-active nginx 2>&1");
$results[] = "Nginx status: " . trim($output);

$output = shell_exec("echo '$sudoPass' | sudo -S systemctl is-active php8.3-fpm 2>&1");
$results[] = "PHP-FPM status: " . trim($output);

$output = shell_exec("echo '$sudoPass' | sudo -S systemctl is-active mysql 2>&1");
$results[] = "MySQL status: " . trim($output);

// Test DB
$conn = @new mysqli('localhost', 'root', '123456', 'socialnet');
if ($conn->connect_error) {
    $results[] = "DB Connection: FAILED - " . $conn->connect_error;
} else {
    $results[] = "DB Connection: OK";
    $conn->close();
}

// Test nginx config
$output = shell_exec("echo '$sudoPass' | sudo -S nginx -t 2>&1");
$results[] = "Nginx test: " . trim($output);

echo "<h2>Fix Results</h2><pre>" . implode("\n", $results) . "</pre>";
?>
