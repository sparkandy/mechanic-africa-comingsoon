# 🔒 Security Audit Report - Mechanic Africa
**Date:** November 12, 2024  
**Environment:** Shared Hosting (https://mechanicafrica.com/)  
**Audit Type:** Pre-Production Security Review

---

## 🚨 CRITICAL SECURITY FLAWS (Fix Immediately)

### 1. **EXPOSED DATABASE FILE** ⚠️ SEVERITY: CRITICAL
**Location:** `contacts.db` (root directory)  
**Risk Level:** 🔴 CRITICAL

**Issue:**
- SQLite database file is directly accessible via web browser
- Contains sensitive customer data (names, emails, car information, IP addresses)
- Contains admin credentials (usernames, password hashes)
- Anyone can download: `https://mechanicafrica.com/contacts.db`

**Current Protection:**
```apache
<Files "contacts.db">
    Order Allow,Deny
    Deny from all
</Files>
```

**Problem:** This `.htaccess` protection may NOT work on all shared hosting servers, especially if:
- `.htaccess` is disabled by host
- Apache's `AllowOverride` is not set to `All`
- Server uses nginx instead of Apache

**Recommended Fix:**
```
OPTION 1 (BEST): Move database outside web root
- Current: /public_html/contacts.db
- Move to: /home/username/private/contacts.db
- Update all PHP files to use absolute path

OPTION 2: Use stronger protection
- Rename to .contacts.db (hidden file)
- Move to /db/ folder with index.php deny access
- Add multiple layers of .htaccess protection
```

**Test Now:**
```bash
# Try accessing from browser:
https://mechanicafrica.com/contacts.db

# If file downloads = CRITICAL VULNERABILITY!
```

---

### 2. **PUBLICLY ACCESSIBLE ADMIN SETUP SCRIPTS** ⚠️ SEVERITY: CRITICAL
**Location:** Multiple PHP files in root directory  
**Risk Level:** 🔴 CRITICAL

**Exposed Files:**
- `init-database.php` - Creates admin users, shows passwords
- `create-super-admin.php` - Creates super admin with displayed credentials
- `add-superadmin.php` - Adds admin users
- `test-form.html` - Testing file with form data

**Risk:**
- Anyone can visit `https://mechanicafrica.com/init-database.php`
- Attackers can create admin accounts
- Default passwords exposed in source code
- Database structure revealed

**Current State:**
```php
// From init-database.php
$defaultPassword = 'MechAdmin2025!'; // EXPOSED IN CODE!

// From create-super-admin.php
$newPassword = 'MechAdmin' . date('Y') . '!$#' . rand(100, 999);
echo "Password: $newPassword"; // DISPLAYED ON SCREEN!
```

**Recommended Fix:**
```
OPTION 1 (IMMEDIATE): Delete or rename files
- Delete: init-database.php, create-super-admin.php, add-superadmin.php
- Or rename with .bak extension
- Keep local copies only

OPTION 2: Add authentication check
if (!defined('SETUP_ALLOWED') || SETUP_ALLOWED !== true) {
    die('Access denied');
}

OPTION 3: Block via .htaccess
<Files "init-database.php">
    Order Allow,Deny
    Deny from all
</Files>
```

---

### 3. **ERROR DISPLAY ENABLED IN PRODUCTION** ⚠️ SEVERITY: HIGH
**Location:** `submit-form.php`  
**Risk Level:** 🟠 HIGH

**Issue:**
```php
// Lines 5-7 in submit-form.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**Risk:**
- Displays full error messages to attackers
- Reveals file paths: `/home/username/public_html/...`
- Shows database structure and queries
- Exposes server configuration
- Helps attackers understand your system

**Example Exposed Info:**
```
Warning: PDO::__construct(): unable to open database: /home/mechanic/contacts.db
Fatal error: Uncaught PDOException: SQLSTATE[HY000]: General error: 1
Stack trace:
  #0 /home/mechanic/public_html/submit-form.php(25): PDO->prepare('INSERT INTO...')
```

**Recommended Fix:**
```php
// For PRODUCTION (shared hosting):
error_reporting(E_ALL);
ini_set('display_errors', 0);  // Never show errors to users
ini_set('log_errors', 1);      // Log to file instead
ini_set('error_log', '/path/to/error.log');

// For DEVELOPMENT (local):
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

### 4. **CORS WILDCARD ALLOWS ANY DOMAIN** ⚠️ SEVERITY: HIGH
**Location:** `submit-form.php` line 10  
**Risk Level:** 🟠 HIGH

**Issue:**
```php
header('Access-Control-Allow-Origin: *');
```

**Risk:**
- Allows ANY website to submit forms to your server
- Attackers can create phishing sites that submit to your database
- No origin verification
- Enables CSRF attacks from external domains

**Recommended Fix:**
```php
// OPTION 1: Remove completely (if not needed)
// Remove all CORS headers

// OPTION 2: Whitelist your domain only
$allowed_origins = ['https://mechanicafrica.com'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}

// OPTION 3: Same-origin only (recommended)
// Don't set CORS headers at all - browser defaults to same-origin
```

---

### 5. **PLACEHOLDER API KEYS IN PRODUCTION** ⚠️ SEVERITY: HIGH
**Location:** `config.php`  
**Risk Level:** 🟠 HIGH

**Issue:**
```php
define('RECAPTCHA_SITE_KEY', '6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
define('RECAPTCHA_SECRET_KEY', '6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
```

**Risk:**
- reCAPTCHA is NOT working (placeholder keys)
- CAPTCHA validation always fails or uses test keys
- Forms are vulnerable to bot spam
- No protection against automated submissions

**Test:**
```php
if (ENABLE_CAPTCHA) {
    // This check is MEANINGLESS with fake keys
}
```

**Recommended Fix:**
1. Get real keys from: https://www.google.com/recaptcha/admin/create
2. Replace placeholder keys
3. Test form submission works
4. Monitor for spam

---

### 6. **HARDCODED CREDENTIALS IN SOURCE CODE** ⚠️ SEVERITY: MEDIUM
**Location:** Multiple files  
**Risk Level:** 🟡 MEDIUM

**Issue:**
- Default passwords in code comments
- Predictable username patterns
- Passwords displayed on screen during setup

**Examples:**
```php
// init-database.php
$defaultPassword = 'MechAdmin2025!'; // VISIBLE IN SOURCE!

// create-super-admin.php
$newUsername = 'mechanic_admin_' . date('md'); // Predictable: mechanic_admin_1112
$newPassword = 'MechAdmin' . date('Y') . '!$#' . rand(100, 999); // Predictable pattern

// README files may contain credentials
```

**Recommended Fix:**
- Remove all hardcoded passwords
- Use environment variables: `$_ENV['ADMIN_PASSWORD']`
- Force password change on first login
- Delete README files with credentials from server

---

## 🟡 HIGH PRIORITY SECURITY ISSUES

### 7. **NO CSRF PROTECTION ON FORMS** ⚠️ SEVERITY: MEDIUM
**Location:** All forms (contact, admin login, user management)  
**Risk Level:** 🟡 MEDIUM

**Issue:**
- No CSRF tokens on forms
- Attackers can create malicious pages that submit forms as logged-in admin
- Example attack: Admin visits attacker's page → Attacker deletes admin account

**Vulnerable Forms:**
- Contact form (submit-form.php)
- Login form (login.php)
- User management forms (user-management.php)
- Admin actions

**Recommended Fix:**
```php
// Generate token
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// In HTML form
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

// Validate on submission
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token validation failed');
}
```

---

### 8. **SESSION FIXATION VULNERABILITY** ⚠️ SEVERITY: MEDIUM
**Location:** `login.php`, session handling  
**Risk Level:** 🟡 MEDIUM

**Issue:**
```php
// Session ID not regenerated after login
$_SESSION['admin_user_id'] = $user['id'];
// Missing: session_regenerate_id(true);
```

**Risk:**
- Attacker can set victim's session ID
- After victim logs in, attacker uses same session ID
- Attacker gains admin access without password

**Recommended Fix:**
```php
// After successful login in login.php
if ($user && password_verify($password, $user['password_hash'])) {
    // Regenerate session ID to prevent fixation
    session_regenerate_id(true);
    
    $_SESSION['admin_user_id'] = $user['id'];
    // ... rest of login code
}
```

---

### 9. **SQL INJECTION RISK IN EDGE CASES** ⚠️ SEVERITY: LOW-MEDIUM
**Location:** Various database queries  
**Risk Level:** 🟡 MEDIUM

**Issue:**
- Most queries use prepared statements ✅ GOOD
- But some dynamic queries may be vulnerable

**Areas to Review:**
```php
// admin.php - Check for dynamic ORDER BY
// user-management.php - Search/filter functionality
// Any queries built with string concatenation
```

**Recommended Fix:**
- Audit ALL database queries
- Whitelist allowed values for ORDER BY, LIMIT, etc.
- Never concatenate user input into SQL

```php
// BAD:
$query = "SELECT * FROM users WHERE id = " . $_GET['id'];

// GOOD:
$query = "SELECT * FROM users WHERE id = ?";
$stmt->execute([$_GET['id']]);
```

---

### 10. **FILE PERMISSION ISSUES** ⚠️ SEVERITY: MEDIUM
**Location:** All files  
**Risk Level:** 🟡 MEDIUM

**Current Permissions:**
```bash
-rw-r--r--  config.php        # World-readable (644)
-rw-r--r--  auth-config.php   # World-readable (644)
-rw-r--r--  contacts.db       # World-readable (644)
```

**Risk:**
- Other users on shared hosting can read your files
- Database can be copied by other accounts
- Config files with API keys readable

**Recommended Fix:**
```bash
# PHP files
chmod 640 *.php        # Owner read/write, group read, no public access

# Database
chmod 600 contacts.db  # Owner read/write only

# Config files
chmod 600 config.php auth-config.php

# Directories
chmod 750 /images /admin

# .htaccess
chmod 644 .htaccess
```

---

### 11. **NO RATE LIMITING IMPLEMENTED** ⚠️ SEVERITY: MEDIUM
**Location:** `submit-form.php`, login forms  
**Risk Level:** 🟡 MEDIUM

**Issue:**
```php
// config.php defines limits but they're not enforced
define('MAX_SUBMISSIONS_PER_HOUR', 10);
define('MAX_SUBMISSIONS_PER_DAY', 50);
// NO CODE ACTUALLY CHECKS THESE!
```

**Risk:**
- Attackers can spam your database
- No protection against brute force on forms
- Server resource exhaustion

**Recommended Fix:**
```php
// Check submission rate
$ip = $_SERVER['REMOTE_ADDR'];
$stmt = $pdo->prepare("
    SELECT COUNT(*) as count 
    FROM contacts 
    WHERE ip_address = ? 
    AND submitted_at > datetime('now', '-1 hour')
");
$stmt->execute([$ip]);
$submissions = $stmt->fetch()['count'];

if ($submissions >= MAX_SUBMISSIONS_PER_HOUR) {
    http_response_code(429);
    die('Too many submissions. Please try again later.');
}
```

---

### 12. **WEAK PASSWORD VALIDATION** ⚠️ SEVERITY: MEDIUM
**Location:** `user-management.php`, admin creation  
**Risk Level:** 🟡 MEDIUM

**Issue:**
- Password requirements defined but not enforced consistently
- No check for common passwords
- No password complexity meter

**Current Requirements:**
```php
define('MIN_PASSWORD_LENGTH', 8);
define('REQUIRE_UPPERCASE', true);
define('REQUIRE_LOWERCASE', true);
define('REQUIRE_NUMBERS', true);
define('REQUIRE_SPECIAL_CHARS', true);
```

**Problem:** Not validated in all user creation paths

**Recommended Fix:**
```php
function validatePassword($password) {
    if (strlen($password) < MIN_PASSWORD_LENGTH) {
        return ['valid' => false, 'error' => 'Password too short'];
    }
    if (REQUIRE_UPPERCASE && !preg_match('/[A-Z]/', $password)) {
        return ['valid' => false, 'error' => 'Must contain uppercase'];
    }
    if (REQUIRE_LOWERCASE && !preg_match('/[a-z]/', $password)) {
        return ['valid' => false, 'error' => 'Must contain lowercase'];
    }
    if (REQUIRE_NUMBERS && !preg_match('/[0-9]/', $password)) {
        return ['valid' => false, 'error' => 'Must contain number'];
    }
    if (REQUIRE_SPECIAL_CHARS && !preg_match('/[^A-Za-z0-9]/', $password)) {
        return ['valid' => false, 'error' => 'Must contain special character'];
    }
    
    // Check against common passwords
    $common = ['password', 'Password123!', 'Admin123!'];
    if (in_array($password, $common)) {
        return ['valid' => false, 'error' => 'Password too common'];
    }
    
    return ['valid' => true];
}
```

---

### 13. **MISSING SECURITY HEADERS** ⚠️ SEVERITY: MEDIUM
**Location:** `.htaccess`, PHP files  
**Risk Level:** 🟡 MEDIUM

**Issue:**
Some security headers are set, but missing:
- Content-Security-Policy (CSP)
- X-Permitted-Cross-Domain-Policies
- Feature-Policy updated headers

**Current Headers:**
```apache
✅ X-Frame-Options: SAMEORIGIN
✅ X-Content-Type-Options: nosniff
✅ X-XSS-Protection: 1; mode=block
✅ Referrer-Policy: strict-origin-when-cross-origin
✅ Permissions-Policy (limited)
❌ Content-Security-Policy: MISSING
❌ X-Permitted-Cross-Domain-Policies: MISSING
```

**Recommended Fix:**
```apache
# Add to .htaccess
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' https://www.google.com https://www.gstatic.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; frame-src https://www.google.com;"
Header always set X-Permitted-Cross-Domain-Policies "none"
```

---

### 14. **NO BACKUP STRATEGY** ⚠️ SEVERITY: MEDIUM
**Location:** Database and files  
**Risk Level:** 🟡 MEDIUM

**Issue:**
- Single SQLite database file
- No automated backups
- If `contacts.db` corrupts or gets deleted → ALL DATA LOST
- Shared hosting may not include backups

**Recommended Fix:**
```bash
# Create backup script (run daily via cron)
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/username/backups"
DB_FILE="/home/username/private/contacts.db"

# Create backup
cp $DB_FILE $BACKUP_DIR/contacts_$DATE.db

# Keep only last 30 days
find $BACKUP_DIR -name "contacts_*.db" -mtime +30 -delete

# Optional: Upload to cloud storage
# rclone copy $BACKUP_DIR remote:backups/
```

**Cron Job:**
```bash
# Add to crontab: Daily backup at 2 AM
0 2 * * * /home/username/scripts/backup-database.sh
```

---

## 🟢 LOW PRIORITY ISSUES

### 15. **EXPOSED BACKUP FILES** ⚠️ SEVERITY: LOW
**Location:** `index-backup-20251112-182813.php`, `index-old-*.html`  
**Risk Level:** 🟢 LOW

**Issue:**
- Backup files in web root
- Can reveal old code, comments, credentials
- Unnecessary files on production

**Files Found:**
- `index-backup-20251112-182813.php`
- `index-old-20251112-183447.html`
- `mechanic-africa-v2 (1).html`

**Recommended Fix:**
```bash
# Delete from production server
rm index-backup-*.php
rm index-old-*.html
rm mechanic-africa-v2*.html
rm test-form.html

# Keep only in version control
```

---

### 16. **VERBOSE ERROR MESSAGES** ⚠️ SEVERITY: LOW
**Location:** Various PHP files  
**Risk Level:** 🟢 LOW

**Issue:**
```php
echo json_encode(['success' => false, 'message' => 'Database connection failed']);
// Reveals database is SQLite
```

**Recommended Fix:**
```php
// Generic error messages
echo json_encode(['success' => false, 'message' => 'An error occurred']);

// Log detailed errors server-side
error_log('Database connection failed: ' . $e->getMessage());
```

---

### 17. **NO HTTPS ENFORCEMENT IN PHP** ⚠️ SEVERITY: LOW
**Location:** All PHP files  
**Risk Level:** 🟢 LOW

**Issue:**
- HTTPS redirect only in `.htaccess`
- If `.htaccess` fails, no PHP-level enforcement

**Recommended Fix:**
```php
// Add to config.php or all entry points
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    if (strpos($_SERVER['HTTP_HOST'], 'mechanicafrica.com') !== false) {
        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $redirect, true, 301);
        exit;
    }
}
```

---

### 18. **INFORMATION DISCLOSURE IN COMMENTS** ⚠️ SEVERITY: LOW
**Location:** PHP source files  
**Risk Level:** 🟢 LOW

**Issue:**
```php
// TODO: Add validation
// HACK: This is temporary
// Username: admin, Password: test123
```

**Recommended Fix:**
- Remove all sensitive comments before deployment
- Don't commit credentials in comments
- Use separate TODO tracking system

---

### 19. **NO INPUT LENGTH LIMITS** ⚠️ SEVERITY: LOW
**Location:** Form processing  
**Risk Level:** 🟢 LOW

**Issue:**
- Users can submit very long strings
- Can cause database issues or DoS

**Recommended Fix:**
```php
$name = substr(trim($input['name'] ?? ''), 0, 100);
$email = substr(trim($input['email'] ?? ''), 0, 255);
$carInfo = substr(trim($input['car'] ?? ''), 0, 500);
```

---

### 20. **DIRECTORY LISTING (Fixed)** ✅ SEVERITY: LOW
**Location:** `.htaccess`  
**Risk Level:** ✅ FIXED

**Status:**
```apache
Options -Indexes  # ✅ Already implemented
```

**Good:** Prevents listing files in directories

---

## 📊 SECURITY SCORECARD

| Category | Score | Status |
|----------|-------|--------|
| Database Security | 3/10 | 🔴 CRITICAL |
| File Security | 4/10 | 🔴 CRITICAL |
| Code Security | 6/10 | 🟡 NEEDS WORK |
| Configuration | 5/10 | 🟡 NEEDS WORK |
| Authentication | 7/10 | 🟡 GOOD |
| Encryption | 8/10 | 🟢 GOOD |
| Error Handling | 3/10 | 🔴 CRITICAL |
| **OVERALL** | **5.1/10** | 🟡 **VULNERABLE** |

---

## 🎯 PRIORITY ACTION PLAN

### IMMEDIATE (Do Before Going Live)
1. ✅ Move `contacts.db` outside web root
2. ✅ Delete admin setup scripts (init-database.php, create-super-admin.php)
3. ✅ Disable error display in submit-form.php
4. ✅ Remove CORS wildcard or restrict to your domain
5. ✅ Get real reCAPTCHA keys and test
6. ✅ Test database file is NOT downloadable
7. ✅ Change all default passwords

### URGENT (Within 24 Hours)
8. ⚠️ Add CSRF protection to all forms
9. ⚠️ Implement session regeneration on login
10. ⚠️ Add rate limiting to forms
11. ⚠️ Fix file permissions (chmod 600 for sensitive files)
12. ⚠️ Delete backup files from server
13. ⚠️ Add HTTPS enforcement in PHP

### IMPORTANT (Within 1 Week)
14. 📋 Implement automated database backups
15. 📋 Add Content-Security-Policy header
16. 📋 Audit all SQL queries for injection risks
17. 📋 Add input length validation
18. 📋 Strengthen password validation
19. 📋 Remove sensitive comments from code
20. 📋 Set up error logging to file

---

## 🛠️ TESTING CHECKLIST

After implementing fixes, test:

```bash
# 1. Database not accessible
curl -I https://mechanicafrica.com/contacts.db
# Expected: 403 Forbidden or 404 Not Found

# 2. Setup scripts removed/blocked
curl -I https://mechanicafrica.com/init-database.php
# Expected: 404 Not Found

# 3. Config files blocked
curl -I https://mechanicafrica.com/config.php
# Expected: 403 Forbidden (shows blank page, not source code)

# 4. HTTPS redirect works
curl -I http://mechanicafrica.com
# Expected: 301 redirect to https://

# 5. Error display off
# Submit invalid form data
# Expected: Generic error, NOT detailed PHP errors

# 6. reCAPTCHA working
# Submit form without CAPTCHA
# Expected: Error about CAPTCHA required

# 7. Rate limiting works
# Submit form 11+ times from same IP within hour
# Expected: "Too many submissions" error

# 8. CSRF protection works
# Submit form without CSRF token
# Expected: "CSRF validation failed" error
```

---

## 📞 NEED HELP?

If you need assistance implementing these fixes:
1. Prioritize CRITICAL issues first
2. Test each fix in development before production
3. Keep backups before making changes
4. Consider hiring a security consultant for shared hosting setup

**Shared Hosting Specific Concerns:**
- Other users can potentially access your files
- Limited ability to move files outside web root
- `.htaccess` protections may not work
- Consider upgrading to VPS or dedicated hosting for better security

---

**Report Generated:** November 12, 2024  
**Next Review:** After implementing CRITICAL fixes  
**Compliance:** Not currently compliant with GDPR, PCI-DSS, or OWASP Top 10

