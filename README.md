# 🌐 SocialNet - Social Network Web Application

A full-featured social network web application built with PHP, MySQL, and Nginx.

## 📋 Prerequisites

- **Linux** (Ubuntu 20.04+ recommended)
- **Nginx** web server
- **PHP 7.4+** with PHP-FPM
- **MySQL 5.7+** or MariaDB
- **PHP Extensions**: php-mysqli
- **Git**

## 🚀 Quick Setup

### 1. Install Dependencies

```bash
sudo apt update
sudo apt install php php-fpm php-mysqli nginx mysql-server git -y
sudo systemctl start nginx mysql
sudo systemctl enable nginx mysql
```

### 2. Clone the Repository

```bash
cd /var/www
sudo git clone https://github.com/YOUR_USERNAME/Social_network.git socialnet
sudo chown -R www-data:www-data socialnet
```

### 3. Import Database

```bash
sudo mysql -u root < /var/www/socialnet/db.sql
```

Or manually:

```bash
sudo mysql -u root
```

```sql
SOURCE /var/www/socialnet/db.sql;
EXIT;
```

### 4. Configure Database Connection

Edit `/var/www/socialnet/includes/config.php` and update the credentials if needed:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');        // Set your MySQL root password
define('DB_NAME', 'socialnet');
```

### 5. Configure Nginx

Create a new Nginx site configuration:

```bash
sudo nano /etc/nginx/sites-available/socialnet
```

Paste the following:

```nginx
server {
    listen 80;
    server_name localhost;

    root /var/www/socialnet;
    index index.php index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-params.conf;
        fastcgi_pass unix:/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable the site and restart Nginx:

```bash
sudo ln -sf /etc/nginx/sites-available/socialnet /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

### 6. Access the Application

- **Sign In**: http://192.168.129.131/socialnet/signin.php
- **Admin (Create User)**: http://localhost/admin/newuser.php

### 7. Test Accounts

| Username | Password | Full Name   |
|----------|----------|-------------|
| admin    | 123456   | Admin User  |
| johndoe  | 123456   | John Doe    |
| janedoe  | 123456   | Jane Doe    |

## 📁 Project Structure

```
Social_network/
├── admin/
│   └── newuser.php          # Admin: Create new user
├── socialnet/
│   ├── signin.php           # Login page
│   ├── index.php            # Home page (list all users)
│   ├── profile.php          # User profile page
│   ├── setting.php          # Edit profile settings
│   ├── about.php            # About page
│   └── signout.php          # Logout
├── includes/
│   ├── config.php           # Database configuration
│   ├── functions.php        # Helper functions
│   ├── menubar.php          # Reusable navigation bar
│   └── session.php          # Session management
├── assets/
│   ├── style.css            # Stylesheet
│   └── script.js            # JavaScript helpers
├── db.sql                   # Database schema & sample data
├── .gitignore               # Git ignore rules
└── README.md                # This file
```

## ✨ Features

- ✅ User authentication (login/logout)
- ✅ Session management
- ✅ Admin panel for user creation
- ✅ Password hashing (bcrypt)
- ✅ User profiles with descriptions
- ✅ Profile editing
- ✅ Responsive design (mobile-friendly)
- ✅ Input validation & error handling
- ✅ XSS prevention (output escaping)
- ✅ Prepared statements (SQL injection prevention)

## 🔒 Security Features

- Passwords hashed with `password_hash()` (bcrypt)
- Password verification with `password_verify()`
- Prepared statements for all database queries
- HTML output escaping with `htmlspecialchars()`
- Session-based authentication
- Input validation on both client and server side

## 📄 License

This project is created for educational purposes.
