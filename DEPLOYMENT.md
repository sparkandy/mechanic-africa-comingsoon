# Deployment Guide for Shared Hosting

This guide explains how to deploy the Mechanic Africa website to shared hosting servers.

## Files to Upload

Upload ALL these files to your shared hosting's public folder (usually `public_html` or `www`):

### Required Files:
- `index.html` - Main website
- `styles.css` - Stylesheet  
- `script.js` - JavaScript functionality
- `submit-form.php` - Form submission handler
- `admin.php` - Admin panel to view submissions
- `mechanic-africa.jpeg` - Hero image
- `.htaccess` - Apache configuration (if supported)

## Shared Hosting Requirements

### Minimum Requirements:
✅ **PHP 7.4+** (PHP 8.x recommended)  
✅ **SQLite support** (enabled by default in most PHP installations)  
✅ **File write permissions** (for database creation)  

### Optional but Recommended:
- Apache with mod_rewrite (for .htaccess support)
- HTTPS/SSL certificate

## Setup Instructions

### 1. **Upload Files**
```bash
# Upload all files to your hosting's public directory
# Usually: public_html/ or www/ or httpdocs/
```

### 2. **Set Permissions**
Make sure your hosting allows:
- PHP file execution (*.php)
- Database file creation (contacts.db will be auto-created)
- Write permissions in the main directory

### 3. **Test the Website**
Visit your domain: `https://yourdomain.com`

### 4. **Test Form Submission**
1. Fill out the contact form
2. Submit it
3. You should see a thank you message

### 5. **Access Admin Panel**
Visit: `https://yourdomain.com/admin.php`
- View all form submissions
- See statistics (total, today, this week)

## File Structure on Server

```
public_html/
├── index.html              # Main website
├── styles.css              # Styles with cache-busting
├── script.js               # Form handling JavaScript
├── submit-form.php         # Form submission endpoint
├── admin.php               # Admin panel
├── mechanic-africa.jpeg    # Hero image
├── contacts.db             # SQLite database (auto-created)
└── .htaccess               # Apache config (if supported)
```

## Database Information

### SQLite Database: `contacts.db`
**Table:** `contacts`

| Column | Type | Description |
|--------|------|-------------|
| id | INTEGER PRIMARY KEY | Auto-increment ID |
| name | TEXT | Customer name |
| email | TEXT | Customer email |
| car_information | TEXT | Car details |
| submitted_at | DATETIME | Submission timestamp |
| ip_address | TEXT | Customer IP address |

### Database Security:
- Database file is protected by .htaccess
- Not directly accessible via web browser
- Only accessible through PHP scripts

## Testing Locally with Docker

For local development/testing:

```bash
# Start the container
./docker.sh start

# Visit: http://localhost:9000
# Test form: Submit contact form
# View admin: http://localhost:9000/admin.php

# Stop container
./docker.sh stop
```

## Form Features

### ✅ **Client-Side Validation**
- All fields required
- Email format validation
- Real-time error messages
- Visual feedback on invalid fields

### ✅ **Server-Side Validation**
- Duplicate validation on server
- SQL injection prevention
- XSS protection through htmlspecialchars()

### ✅ **User Experience**
- Loading states during submission
- Thank you message after success
- Error handling for network issues
- Option to submit another request

### ✅ **Admin Features**
- View all submissions
- Statistics dashboard
- Responsive admin panel
- Export-ready data display

## Security Features

### 🔒 **Security Measures**
- PDO prepared statements (SQL injection prevention)
- Input sanitization and validation
- CSRF protection headers
- Database file access denied via .htaccess
- XSS protection in admin panel

### 🛡️ **HTTP Headers**
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- X-XSS-Protection: 1; mode=block
- CORS headers for form submission

## Troubleshooting

### Form Not Submitting?
1. Check if PHP is enabled on your hosting
2. Verify write permissions in directory
3. Check browser console for JavaScript errors
4. Ensure .htaccess is uploaded (if using Apache)

### Database Errors?
1. Check if SQLite is enabled: `php -m | grep sqlite`
2. Verify write permissions for database file creation
3. Check PHP error logs on your hosting panel

### 404 Errors on PHP Files?
1. Ensure PHP files have .php extension
2. Check if your hosting supports PHP 7.4+
3. Verify files are uploaded to correct directory

### Admin Panel Not Working?
1. Visit: `https://yourdomain.com/admin.php` directly
2. Check if database file exists and has data
3. Verify PHP error logs

## Performance Tips

### 🚀 **Optimization**
- Static files cached for 1 year
- Gzip compression enabled
- Optimized CSS and JavaScript
- Minimal database queries

### 📱 **Mobile Optimized**
- Responsive design
- Touch-friendly form inputs
- Mobile-first approach
- Fast loading on mobile networks

## Support

If you encounter issues:
1. Check your hosting provider's PHP version
2. Verify SQLite support is enabled
3. Ensure proper file permissions
4. Check error logs in your hosting control panel

---

**🎉 Your Mechanic Africa website is now ready for shared hosting deployment!**