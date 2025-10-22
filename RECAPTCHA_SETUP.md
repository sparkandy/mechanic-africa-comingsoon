# reCAPTCHA Setup Guide

This guide explains how to set up Google reCAPTCHA for the Mechanic Africa contact form.

## Step 1: Get reCAPTCHA Keys

1. **Go to Google reCAPTCHA Admin Console:**
   https://www.google.com/recaptcha/admin/create

2. **Create a new site:**
   - **Label:** Mechanic Africa
   - **reCAPTCHA type:** Choose "reCAPTCHA v2" → "I'm not a robot" Checkbox
   - **Domains:** Add your domain (e.g., `yourdomain.com`)
   - For testing: Add `localhost` and `127.0.0.1`
   - **Accept Terms of Service**
   - Click **Submit**

3. **Copy your keys:**
   - **Site Key** (starts with `6Lc...`) - for frontend
   - **Secret Key** (starts with `6Lc...`) - for backend

## Step 2: Configure the Website

### Option A: Using config.php (Recommended)

1. **Edit `config.php`:**
   ```php
   // Replace these placeholder keys with your actual keys
   define('RECAPTCHA_SITE_KEY', 'YOUR_SITE_KEY_HERE');
   define('RECAPTCHA_SECRET_KEY', 'YOUR_SECRET_KEY_HERE');
   ```

2. **Use index.php instead of index.html:**
   - The system will automatically use your keys from config.php
   - Upload both `index.php` and `index.html` (as fallback)

### Option B: Manual Setup

If you prefer to use static HTML (`index.html`):

1. **Edit `index.html`:**
   Find this line:
   ```html
   <div class="g-recaptcha" data-sitekey="6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"></div>
   ```
   
   Replace `6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX` with your **Site Key**

2. **Edit `submit-form.php`:**
   Find this line:
   ```php
   $recaptchaSecretKey = RECAPTCHA_SECRET_KEY;
   ```
   
   Replace with:
   ```php
   $recaptchaSecretKey = 'YOUR_SECRET_KEY_HERE';
   ```

## Step 3: Upload Files

Upload these files to your hosting:

### Required Files:
- `index.php` (or `index.html` if using manual setup)
- `config.php` (with your actual keys)
- `submit-form.php`
- `styles.css`
- `script.js`
- `admin.php`
- `mechanic-africa.jpeg`
- `.htaccess`

## Step 4: Test the Setup

### Local Testing (Docker):
```bash
# Restart the container
./docker.sh restart

# Visit: http://localhost:9000
# The CAPTCHA will show as "localhost is not registered"
# This is normal for local testing
```

### Production Testing:
1. Upload files to your domain
2. Visit your website
3. Fill out the form
4. Complete the CAPTCHA
5. Submit and verify it works

## Step 5: Troubleshooting

### Common Issues:

#### 1. "ERROR for site owner: Invalid domain for site key"
- **Solution:** Add your domain to the reCAPTCHA admin console
- Make sure domain matches exactly (with/without www)

#### 2. "CAPTCHA verification failed"
- **Solution:** Check your secret key in `config.php`
- Verify the keys are copied correctly (no extra spaces)

#### 3. CAPTCHA not showing
- **Solution:** Check browser console for JavaScript errors
- Ensure `https://www.google.com/recaptcha/api.js` is loading

#### 4. Form submits without CAPTCHA
- **Solution:** Check `config.php` - ensure `ENABLE_CAPTCHA` is `true`
- Verify JavaScript is not disabled

### Testing Commands:

```bash
# Test form submission (will fail CAPTCHA validation)
curl -X POST https://yourdomain.com/submit-form.php \
  -d "name=Test&email=test@example.com&car=Test Car"

# Should return: "Please complete the CAPTCHA verification"
```

## Step 6: Security Features

### Included Protection:
✅ **Server-side CAPTCHA validation**  
✅ **Client-side CAPTCHA validation**  
✅ **Form field validation**  
✅ **SQL injection prevention**  
✅ **XSS protection**  
✅ **CAPTCHA reset on error**  

### Optional Enhancements:
- **Rate limiting** (config ready, needs implementation)
- **IP-based blocking** for repeat offenders
- **Honeypot fields** for additional bot protection

## Step 7: Maintenance

### Monitor Submissions:
- Visit: `https://yourdomain.com/admin.php`
- Check for unusual patterns or spam
- Review CAPTCHA success/failure rates

### Update Keys:
If you need to change domains or regenerate keys:
1. Update in Google reCAPTCHA admin console
2. Update `config.php` with new keys
3. Test thoroughly

## Production Checklist

Before going live:

- [ ] Replace placeholder keys with real reCAPTCHA keys
- [ ] Test form submission works
- [ ] Test CAPTCHA validation works
- [ ] Test admin panel shows submissions
- [ ] Verify error handling works
- [ ] Check mobile responsiveness
- [ ] Test with different browsers

---

**🔒 Your form is now protected against bots and spam!**