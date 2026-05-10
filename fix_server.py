import subprocess
import os

sudo_pass = "45464748"

def run_sudo(cmd):
    """Run a command with sudo"""
    proc = subprocess.Popen(
        f"echo {sudo_pass} | sudo -S {cmd}",
        shell=True,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE
    )
    stdout, stderr = proc.communicate(timeout=30)
    return stdout.decode() + stderr.decode(), proc.returncode

# 1. Write Nginx config
nginx_config = """server {
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
        try_files $uri $uri/ =404;
    }
}
"""

# Write config via tee
proc = subprocess.Popen(
    f"echo {sudo_pass} | sudo -S tee /etc/nginx/sites-available/default",
    shell=True,
    stdin=subprocess.PIPE,
    stdout=subprocess.PIPE,
    stderr=subprocess.PIPE
)
stdout, stderr = proc.communicate(input=nginx_config.encode(), timeout=10)
print(f"[1] Nginx config write: rc={proc.returncode}")
print(f"    stderr: {stderr.decode().strip()}")

# 2. Set permissions
out, rc = run_sudo("chmod 755 /home/mai")
print(f"[2] chmod /home/mai: rc={rc}")

out, rc = run_sudo("chmod -R 755 /home/mai/socialnet")
print(f"[3] chmod /home/mai/socialnet: rc={rc}")

# 3. Restart PHP-FPM
out, rc = run_sudo("systemctl restart php8.3-fpm")
print(f"[4] PHP-FPM restart: rc={rc} {out.strip()}")

# 4. Restart Nginx
out, rc = run_sudo("systemctl restart nginx")
print(f"[5] Nginx restart: rc={rc} {out.strip()}")

# 5. Restart MySQL
out, rc = run_sudo("systemctl restart mysql")
print(f"[6] MySQL restart: rc={rc} {out.strip()}")

# 6. Check statuses
out, rc = run_sudo("systemctl is-active nginx")
print(f"[7] Nginx active: {out.strip()}")

out, rc = run_sudo("systemctl is-active php8.3-fpm")
print(f"[8] PHP-FPM active: {out.strip()}")

out, rc = run_sudo("systemctl is-active mysql")
print(f"[9] MySQL active: {out.strip()}")

# 7. Nginx config test
out, rc = run_sudo("nginx -t")
print(f"[10] Nginx test: {out.strip()}")

# 8. Check DB connection
import socket
sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
result = sock.connect_ex(('localhost', 3306))
if result == 0:
    print("[11] MySQL port 3306: OPEN")
else:
    print(f"[11] MySQL port 3306: CLOSED (rc={result})")
sock.close()

# 9. Check HTTP
sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
result = sock.connect_ex(('localhost', 80))
if result == 0:
    print("[12] HTTP port 80: OPEN")
else:
    print(f"[12] HTTP port 80: CLOSED (rc={result})")
sock.close()

print("\nDone!")
