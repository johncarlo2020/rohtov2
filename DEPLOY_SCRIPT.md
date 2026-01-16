# Deployment Guide - beautylightclub.com

Complete step-by-step guide to deploy this Laravel application on Ubuntu with Apache.

## Prerequisites
- Ubuntu server with sudo access
- Apache2 installed
- PHP 8.1+ installed with required extensions
- MySQL/MariaDB installed
- Composer installed
- Node.js & NPM installed
- Certbot installed for SSL

---

## Step 1: Clone or Pull Repository

```bash
# If first time deployment
cd /var/www
sudo git clone https://github.com/johncarlo2020/rohtov2.git yslBeautyClub

# If updating existing deployment
cd /var/www/yslBeautyClub
sudo git pull origin main
```

---

## Step 2: Install PHP Dependencies

```bash
cd /var/www/yslBeautyClub
composer install --optimize-autoloader --no-dev
```

For development environment, use:
```bash
composer install
```

---

## Step 3: Install Node Dependencies & Build Assets

```bash
npm install
npm run build
```

For development:
```bash
npm run dev
```

---

## Step 4: Configure Environment File

```bash
# Copy example environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file with your settings
nano .env
```

**Required .env configurations:**
```env
APP_NAME=Kose
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://beautylightclub.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yslNightClub
DB_USERNAME=newuser
DB_PASSWORD=password

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=1804777
PUSHER_APP_KEY=60de59064bcf7cfb6d63
PUSHER_APP_SECRET=a545f1f3ddea7427b33f
PUSHER_APP_CLUSTER=ap1
```

---

## Step 5: Create MySQL Database and User

```bash
# Login to MySQL as root
sudo mysql -u root

# Create database
CREATE DATABASE yslNightClub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create user and grant privileges
CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON yslNightClub.* TO 'newuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Important:** If you get "Access denied for user 'root'@'localhost'" error:
```bash
# Fix MySQL root authentication
sudo mysql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'your_root_password';
FLUSH PRIVILEGES;
EXIT;
```

---

## Step 6: Run Database Migrations and Seeders

```bash
cd /var/www/yslBeautyClub

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Or run both together
php artisan migrate --seed

# Clear and cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 7: Set File Permissions

```bash
# Set ownership to Apache user
sudo chown -R www-data:www-data /var/www/yslBeautyClub

# Set directory permissions
sudo chmod -R 755 /var/www/yslBeautyClub

# Set storage and cache permissions
sudo chmod -R 775 /var/www/yslBeautyClub/storage
sudo chmod -R 775 /var/www/yslBeautyClub/bootstrap/cache
```

---

## Step 8: Create Apache Virtual Host Configuration

```bash
# Create configuration file
sudo nano /etc/apache2/sites-available/beautylightclub.com.conf
```

**Add this configuration:**
```apache
<VirtualHost *:80>
        ServerAdmin eeprojectmain@gmail.com

        ServerName beautylightclub.com
        ServerAlias www.beautylightclub.com

        DocumentRoot /var/www/yslBeautyClub/public

        <Directory /var/www/yslBeautyClub/public/>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/beautylightclub-error.log
        CustomLog ${APACHE_LOG_DIR}/beautylightclub-access.log combined

        <IfModule mod_dir.c>
            DirectoryIndex index.php index.html index.htm
        </IfModule>

RewriteEngine on
RewriteCond %{SERVER_NAME} =www.beautylightclub.com [OR]
RewriteCond %{SERVER_NAME} =beautylightclub.com
RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
</VirtualHost>
```

---

## Step 9: Enable Apache Modules and Site

```bash
# Enable required Apache modules
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers

# Enable the site
sudo a2ensite beautylightclub.com.conf

# Test Apache configuration
sudo apache2ctl configtest

# Reload Apache
sudo systemctl reload apache2
```

---

## Step 10: Obtain SSL Certificate with Certbot

```bash
# Get SSL certificate from Let's Encrypt
sudo certbot --apache -d beautylightclub.com -d www.beautylightclub.com

# Follow the prompts:
# - Enter email address
# - Agree to terms of service
# - Choose whether to share email with EFF
# - Certificate will be automatically configured
```

**Certbot will:**
- Obtain SSL certificate
- Create SSL virtual host configuration
- Set up automatic renewal
- Configure HTTP to HTTPS redirect

---

## Step 11: Verify Installation

```bash
# Check Apache status
sudo systemctl status apache2

# Check SSL certificate
sudo certbot certificates | grep -A 10 beautylightclub

# Test HTTPS connection
curl -I https://beautylightclub.com

# Check Laravel application
php artisan --version
```

---

## Step 12: Set Up Scheduled Tasks (Optional)

If your application uses Laravel's task scheduler:

```bash
# Edit crontab for www-data user
sudo crontab -u www-data -e

# Add this line:
* * * * * cd /var/www/yslBeautyClub && php artisan schedule:run >> /dev/null 2>&1
```

---

## Step 13: Configure Queue Workers (Optional)

If your application uses queues:

```bash
# Install Supervisor
sudo apt install supervisor

# Create worker configuration
sudo nano /etc/supervisor/conf.d/yslbeautyclub-worker.conf
```

**Add:**
```ini
[program:yslbeautyclub-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/yslBeautyClub/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/yslBeautyClub/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Update Supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start yslbeautyclub-worker:*
```

---

## Troubleshooting

### PHP Warnings about Modules Already Loaded
Edit PHP configuration files and remove duplicate extension entries:
```bash
sudo nano /etc/php/8.1/cli/php.ini
# Remove duplicate lines for: opcache, curl, mbstring
```

### Database Access Denied Error
Check MySQL user permissions:
```bash
sudo mysql
SHOW GRANTS FOR 'newuser'@'localhost';
```

### 500 Internal Server Error
Check Laravel logs:
```bash
tail -f /var/www/yslBeautyClub/storage/logs/laravel.log
```

Check Apache logs:
```bash
sudo tail -f /var/log/apache2/beautylightclub-error.log
```

### Storage Not Writable
Reset permissions:
```bash
sudo chown -R www-data:www-data /var/www/yslBeautyClub/storage
sudo chmod -R 775 /var/www/yslBeautyClub/storage
```

### Clear All Laravel Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

---

## Updating the Application

```bash
# Navigate to project directory
cd /var/www/yslBeautyClub

# Put application in maintenance mode
php artisan down

# Pull latest changes
sudo git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Bring application back online
php artisan up
```

---

## Security Checklist

- [ ] Set `APP_ENV=production` in .env
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Use strong database passwords
- [ ] Keep all packages updated
- [ ] Regular backups of database and files
- [ ] SSL certificate auto-renewal enabled
- [ ] Firewall configured (UFW)
- [ ] Disable directory listing in Apache
- [ ] Keep server packages updated

---

## Useful Commands

```bash
# View application logs
tail -f storage/logs/laravel.log

# View Apache error logs
sudo tail -f /var/log/apache2/beautylightclub-error.log

# Restart Apache
sudo systemctl restart apache2

# Check Apache virtual hosts
sudo apachectl -S

# Renew SSL certificate manually
sudo certbot renew

# Check SSL certificate expiry
sudo certbot certificates
```

---

## Site URLs

- **Production:** https://beautylightclub.com
- **www:** https://www.beautylightclub.com
- **Server IP:** (Check with `curl ifconfig.me`)

---

## Support

For issues or questions, refer to:
- Laravel Documentation: https://laravel.com/docs
- Apache Documentation: https://httpd.apache.org/docs/
- Certbot Documentation: https://certbot.eff.org/docs/

---

**Last Updated:** January 16, 2026
**Server:** Ubuntu with Apache2, PHP 8.1, MySQL
**Framework:** Laravel (version as per composer.json)
