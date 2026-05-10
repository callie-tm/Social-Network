#!/bin/bash
# Fix script - update Nginx config, copy files, restart services

echo "45464748" | sudo -S bash -c '
set -e

# 1. Update Nginx config - change root to /home/mai
cat > /etc/nginx/sites-available/default << NGINXEOF
server {
    listen 80 default_server;
    listen [::]:80 default_server;

    server_name _;

    root /home/mai;

    index index.html index.htm index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }

    location / {
        try_files $uri $uri/ =404;
    }
}
NGINXEOF

echo "[OK] Nginx config updated"

# 2. Set permissions on home dir so nginx can read
chmod 755 /home/mai
chmod -R 755 /home/mai/socialnet

echo "[OK] Permissions set"

# 3. Restart services
systemctl restart php8.3-fpm 2>/dev/null || systemctl restart php8.2-fpm 2>/dev/null || systemctl restart php8.1-fpm 2>/dev/null || echo "[WARN] Could not restart PHP-FPM"
systemctl restart nginx
systemctl restart mysql 2>/dev/null || systemctl restart mariadb 2>/dev/null || echo "[WARN] Could not restart MySQL"

echo "[OK] Services restarted"

# 4. Status check
echo ""
echo "=== Service Status ==="
echo -n "nginx: "; systemctl is-active nginx
echo -n "php-fpm: "; systemctl is-active php8.3-fpm 2>/dev/null || systemctl is-active php8.2-fpm 2>/dev/null || systemctl is-active php8.1-fpm 2>/dev/null || echo "unknown"
echo -n "mysql: "; systemctl is-active mysql 2>/dev/null || systemctl is-active mariadb 2>/dev/null || echo "unknown"

# 5. Test nginx config
nginx -t

echo ""
echo "Done! Access http://localhost/socialnet/signin.php"
'
