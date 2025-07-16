# Deploy Stemlio to Hostinger - Complete Guide

## Step 1: Get Hostinger Hosting

### Best Deal:
1. Go to https://www.hostinger.com
2. Choose **Premium Shared Hosting** plan ($2.99/month)
3. Select **48 months** for the best price (~$143 total)
   - Or choose 12 months for ~$48 total
4. Use coupon code **WPBEGINNER** for extra discount

### What You Get:
- 100 websites
- 100GB SSD storage
- Free domain name
- Free SSL certificate
- 99.9% uptime guarantee
- 24/7 support

## Step 2: Initial Setup

After purchase, you'll receive:
- hPanel login (Hostinger's control panel)
- Temporary domain (like `yoursite.hostingersite.com`)

1. Login to hPanel at https://hpanel.hostinger.com
2. Set up your free domain or use the temporary one

## Step 3: Access Your Hosting

### Option A: File Manager (Easiest)
1. In hPanel, go to **Files → File Manager**
2. Navigate to `public_html` folder
3. Delete any default files

### Option B: FTP Access
1. In hPanel, go to **Files → FTP Accounts**
2. Create FTP account
3. Use FileZilla or any FTP client:
   - Host: Your FTP host
   - Username: Your FTP username
   - Password: Your FTP password
   - Port: 21

## Step 4: Create Database

1. In hPanel, go to **Databases → MySQL Databases**
2. Click **Create Database**
3. Enter:
   - Database name: `stemlio`
   - Username: `stemlio_user`
   - Password: (generate strong password)
4. Save these credentials!

## Step 5: Upload Stemlio Files

### Using File Manager:
1. Go to **File Manager**
2. Navigate to `public_html`
3. Click **Upload Files**
4. Select all Stemlio files EXCEPT:
   - `.git` folder
   - `feed_output.html`
   - `cookies.txt`
   - `admin_cookies.txt`
   - `DEPLOY_*.md` files
   - `RUN_ON_MAC.md`

### Using FTP:
```bash
# From your local machine
cd /Users/conradwichmann/Desktop/stemlio/v1/stemlio
ftp your-ftp-host
# Login with credentials
put -r * 
# This uploads all files
```

## Step 6: Update Configuration

1. In File Manager, navigate to `public_html/includes`
2. Edit `config.php`
3. Update with your Hostinger database details:

```php
<?php
error_reporting(0);

$CONF = $TMPL = array();

// The MySQL credentials (from Step 4)
$CONF['host'] = 'localhost';
$CONF['user'] = 'u123456789_stemlio';  // Your actual username
$CONF['pass'] = 'YourDatabasePassword'; // Your actual password
$CONF['name'] = 'u123456789_stemlio';  // Your actual database name
$CONF['port'] = 3306;

// The Installation URL
$CONF['url'] = 'https://yourdomain.com'; // Your actual domain

// The Notifications e-mail
$CONF['email'] = 'notifications@yourdomain.com';

// Rest of the config remains the same...
```

## Step 7: Import Database

1. In hPanel, go to **Databases → phpMyAdmin**
2. Click on your database name
3. Click **Import** tab
4. Click **Choose File** and select `database.sql`
5. Scroll down and click **Go**

## Step 8: Set File Permissions

In File Manager:
1. Right-click on `uploads` folder
2. Select **Permissions**
3. Set to `777` (read, write, execute for all)
4. Repeat for `cache` folder

## Step 9: Configure .htaccess

Create/edit `.htaccess` file in `public_html`:
```apache
RewriteEngine On

# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]

# Remove index.php from URLs
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?a=$1 [QSA,L]

# Security headers
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"

# PHP settings
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value max_execution_time 300
php_value max_input_time 300
```

## Step 10: Test Your Site

1. Visit your domain: `https://yourdomain.com`
2. Login with default admin:
   - Admin panel: `https://yourdomain.com/index.php?a=admin`
   - Username: `admin`
   - Password: `password`
3. **IMPORTANT**: Change the admin password immediately!

## Security Steps (Do This Now!)

### 1. Change Admin Password
```sql
-- In phpMyAdmin, run:
UPDATE admin SET password = MD5('YourNewSecurePassword') WHERE username = 'admin';
```

### 2. Create Your User Account
1. Register a new account on the site
2. In phpMyAdmin, make yourself verified:
```sql
UPDATE users SET verified = 1 WHERE username = 'yourusername';
```

### 3. Secure File Permissions
After setup, tighten permissions:
- Set `config.php` to 644
- Set other PHP files to 644
- Keep `uploads/` and `cache/` at 777

## Performance Optimization

### 1. Enable Hostinger Cache
In hPanel → **Advanced → Cache Manager** → Enable

### 2. Use Cloudflare (Free CDN)
1. Sign up at cloudflare.com
2. Add your site
3. Update nameservers in Hostinger
4. Enable caching and security features

### 3. Optimize Images
In `includes/classes.php`, ensure image uploads are compressed

## Backup Strategy

### Automatic Backups:
1. In hPanel → **Files → Backups**
2. Hostinger does weekly backups automatically

### Manual Backup Script:
Create `backup.php` in a secure folder:
```php
<?php
// Only run from command line
if (php_sapi_name() !== 'cli') die('CLI only');

$date = date('Y-m-d');
$backup_dir = '/home/u123456789/backups/';

// Backup database
exec("mysqldump -u {$CONF['user']} -p{$CONF['pass']} {$CONF['name']} > {$backup_dir}db_{$date}.sql");

// Backup files
exec("tar -czf {$backup_dir}files_{$date}.tar.gz /home/u123456789/public_html/");

// Keep only last 7 days
exec("find {$backup_dir} -name '*.sql' -mtime +7 -delete");
exec("find {$backup_dir} -name '*.tar.gz' -mtime +7 -delete");
```

## Troubleshooting

### White Screen/Blank Page:
1. Check error logs in hPanel → **Advanced → PHP Configuration → Error Log**
2. Temporarily enable errors in `config.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

### Database Connection Error:
- Verify credentials in `config.php` match hPanel database details
- Ensure database user has ALL privileges

### Slow Performance:
1. Enable LiteSpeed Cache in hPanel
2. Reduce image sizes
3. Consider upgrading to Business plan if needed

## Support

- **Hostinger Support**: 24/7 live chat in hPanel
- **Community**: Hostinger has active forums
- **Your App Issues**: Check Apache error logs

## Next Steps

1. Configure email settings for notifications
2. Set up a custom logo/branding
3. Create categories and initial content
4. Invite users to join
5. Monitor usage in hPanel dashboard

## Costs Summary

- **Initial**: $2.99/month × 48 months = $143.52
- **After 4 years**: Renewal at $7.99/month
- **Domain**: Free for first year, then ~$10-15/year
- **Total first 4 years**: ~$144