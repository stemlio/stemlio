# Password Security Upgrade Guide

## Current Issues:
- Uses MD5 (broken since 2005)
- No salt (rainbow table vulnerable)
- Stores passwords in plain text in cookies

## Quick Fix for Admin Password:

In phpMyAdmin, run this to set a bcrypt password:
```sql
-- First, add new column for secure passwords
ALTER TABLE `admin` ADD COLUMN `password_hash` VARCHAR(255) NULL AFTER `password`;

-- Then update admin password (example with 'SecurePass123!')
UPDATE admin SET password_hash = '$2y$10$YourHashHere' WHERE username = 'admin';
```

## To Generate Bcrypt Hash:

### Option 1: Online (for testing only)
- https://bcrypt-generator.com/
- Enter your password
- Copy the hash

### Option 2: PHP Script
```php
<?php
echo password_hash('YourPasswordHere', PASSWORD_DEFAULT);
?>
```

### Option 3: Command Line
```bash
# Install htpasswd if needed
brew install httpd

# Generate bcrypt hash
htpasswd -bnBC 10 "" YourPasswordHere | tr -d ':\n' | sed 's/$2y/$2b/'
```

## Full Security Upgrade:

To properly secure the application:

1. **Update Password Storage**:
   - Change from MD5 to bcrypt
   - Add password_hash column
   - Update login verification

2. **Fix Cookie Security**:
   - Don't store passwords in cookies
   - Use secure session tokens
   - Set HttpOnly and Secure flags

3. **Add CSRF Protection**:
   - Generate tokens for forms
   - Validate on submission

4. **SQL Injection Prevention**:
   - Use prepared statements
   - Escape all user input

5. **XSS Prevention**:
   - Escape output
   - Content Security Policy

## Immediate Actions:

1. **Change admin password** via phpMyAdmin
2. **Don't use common passwords**
3. **Enable HTTPS** (already done with Hostinger)
4. **Regular backups**

## Modern Authentication Example:

```php
// Registration (storing password)
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
// Store $password_hash in database

// Login (verifying password)
if (password_verify($_POST['password'], $user['password_hash'])) {
    // Password is correct
    $_SESSION['user_id'] = $user['id'];
    // Don't store password in session/cookie!
} else {
    // Invalid password
}
```

## Should You Upgrade?

**YES if**:
- Site will have real users
- Storing sensitive data
- Want to follow best practices

**Maybe later if**:
- Just testing/learning
- No real users yet
- Planning to use different software

The current MD5 is "good enough" for testing but should be upgraded before going live with real users.