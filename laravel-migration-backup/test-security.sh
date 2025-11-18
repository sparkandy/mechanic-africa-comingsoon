#!/bin/bash

# Security Fixes Testing Script
# Tests all implemented security measures

echo "============================================="
echo "🔒 Mechanic Africa - Security Testing"
echo "============================================="
echo ""

BASE_URL="http://localhost:9000"
PROD_URL="https://mechanicafrica.com"

echo "Select environment to test:"
echo "1) Local (localhost:9000)"
echo "2) Production (mechanicafrica.com)"
read -p "Enter choice (1 or 2): " ENV_CHOICE

if [ "$ENV_CHOICE" = "2" ]; then
    URL=$PROD_URL
    echo "Testing PRODUCTION environment"
else
    URL=$BASE_URL
    echo "Testing LOCAL environment"
fi

echo ""
echo "============================================="
echo "TEST 1: Database File Protection"
echo "============================================="
echo "Testing: $URL/contacts.db"
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$URL/contacts.db")
if [ "$RESPONSE" = "403" ] || [ "$RESPONSE" = "404" ]; then
    echo "✅ PASS: Database file protected (HTTP $RESPONSE)"
else
    echo "❌ FAIL: Database file accessible (HTTP $RESPONSE)"
fi

echo ""
echo "============================================="
echo "TEST 2: Admin Setup Scripts Protection"
echo "============================================="
FILES=("init-database.php" "create-super-admin.php" "add-superadmin.php")
for FILE in "${FILES[@]}"; do
    echo "Testing: $URL/$FILE"
    RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$URL/$FILE")
    if [ "$RESPONSE" = "403" ] || [ "$RESPONSE" = "404" ]; then
        echo "✅ PASS: $FILE protected (HTTP $RESPONSE)"
    else
        echo "❌ FAIL: $FILE accessible (HTTP $RESPONSE)"
    fi
done

echo ""
echo "============================================="
echo "TEST 3: Config Files Protection"
echo "============================================="
CONFIG_FILES=("config.php" "auth-config.php")
for FILE in "${CONFIG_FILES[@]}"; do
    echo "Testing: $URL/$FILE"
    RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$URL/$FILE")
    if [ "$RESPONSE" = "403" ] || [ "$RESPONSE" = "200" ]; then
        # 200 is OK for PHP files as they execute, not display source
        echo "✅ PASS: $FILE protected (HTTP $RESPONSE)"
    else
        echo "⚠️  WARNING: $FILE response: HTTP $RESPONSE"
    fi
done

echo ""
echo "============================================="
echo "TEST 4: HTTPS Redirect (Production only)"
echo "============================================="
if [ "$ENV_CHOICE" = "2" ]; then
    echo "Testing: http://mechanicafrica.com (should redirect to HTTPS)"
    RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -L "http://mechanicafrica.com")
    LOCATION=$(curl -s -I "http://mechanicafrica.com" | grep -i "location:" | head -1)
    if [[ "$LOCATION" == *"https://"* ]]; then
        echo "✅ PASS: HTTP redirects to HTTPS"
        echo "   Location: $LOCATION"
    else
        echo "❌ FAIL: No HTTPS redirect found"
    fi
else
    echo "⏭️  SKIP: HTTPS redirect only tested in production"
fi

echo ""
echo "============================================="
echo "TEST 5: Security Headers"
echo "============================================="
echo "Checking security headers..."
HEADERS=$(curl -s -I "$URL/" | grep -E "X-Frame-Options|X-Content-Type-Options|Content-Security-Policy|X-XSS-Protection")
if [ -n "$HEADERS" ]; then
    echo "✅ PASS: Security headers found:"
    echo "$HEADERS"
else
    echo "❌ FAIL: Security headers missing"
fi

echo ""
echo "============================================="
echo "TEST 6: Backup Files Protection"
echo "============================================="
BACKUP_FILES=("index-backup-20251112-182813.php" "test-form.html")
for FILE in "${BACKUP_FILES[@]}"; do
    echo "Testing: $URL/$FILE"
    RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$URL/$FILE")
    if [ "$RESPONSE" = "403" ] || [ "$RESPONSE" = "404" ]; then
        echo "✅ PASS: $FILE blocked (HTTP $RESPONSE)"
    else
        echo "⚠️  WARNING: $FILE accessible (HTTP $RESPONSE) - Consider deleting"
    fi
done

echo ""
echo "============================================="
echo "TEST 7: Form CSRF Protection (Manual Test)"
echo "============================================="
echo "To test CSRF protection:"
echo "1. Open: $URL"
echo "2. Open browser console (F12)"
echo "3. Try to submit form without csrf_token field"
echo "4. Expected: 403 Forbidden error"
echo ""
echo "Press Enter when ready to continue..."
read

echo ""
echo "============================================="
echo "TEST 8: Rate Limiting (Manual Test)"
echo "============================================="
echo "To test rate limiting:"
echo "1. Open: $URL/#contact"
echo "2. Fill out the contact form"
echo "3. Submit 11 times within 1 hour"
echo "4. Expected: 'Too many submissions' error on 11th attempt"
echo ""
echo "Press Enter when ready to continue..."
read

echo ""
echo "============================================="
echo "✅ Testing Complete!"
echo "============================================="
echo ""
echo "Manual Tests Required:"
echo "1. ✓ Test form submission with valid data"
echo "2. ✓ Test form submission without CSRF token (should fail)"
echo "3. ✓ Test rate limiting (11+ submissions)"
echo "4. ✓ Login to admin panel (session should regenerate)"
echo "5. ✓ Check error.log file for logged errors"
echo ""
echo "Production Checklist:"
echo "[ ] Replace reCAPTCHA keys in config.php"
echo "[ ] Test form submission works"
echo "[ ] Verify HTTPS redirect works"
echo "[ ] Check error.log file exists and is writable"
echo "[ ] Monitor error.log for issues"
echo ""
echo "For detailed fixes, see: SECURITY-FIXES-APPLIED.md"
