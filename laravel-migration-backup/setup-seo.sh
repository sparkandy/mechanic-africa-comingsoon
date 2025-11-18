#!/bin/bash

# Mechanic Africa - Post-SEO Setup Script
# Run this on your production server after uploading files

echo "========================================="
echo "Mechanic Africa - SEO Verification"
echo "========================================="
echo ""

# Check if files exist
echo "✓ Checking SEO files..."
files=("index.php" "robots.txt" "sitemap.xml" ".htaccess" "404.html")
for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "  ✓ $file exists"
    else
        echo "  ✗ $file MISSING!"
    fi
done

echo ""
echo "========================================="
echo "Next Steps:"
echo "========================================="
echo ""
echo "1. FAVICON SETUP"
echo "   - Visit: https://realfavicongenerator.net/"
echo "   - Upload your logo"
echo "   - Download favicon package"
echo "   - Upload to website root"
echo ""
echo "2. GOOGLE SEARCH CONSOLE"
echo "   - Visit: https://search.google.com/search-console"
echo "   - Add property: https://mechanicafrica.com"
echo "   - Verify ownership (HTML file method)"
echo "   - Submit sitemap: https://mechanicafrica.com/sitemap.xml"
echo ""
echo "3. GOOGLE ANALYTICS"
echo "   - Visit: https://analytics.google.com"
echo "   - Create property for mechanicafrica.com"
echo "   - Get tracking code (GA4)"
echo "   - Add to index.php before </head>"
echo ""
echo "4. TEST WEBSITE"
echo "   - Open: https://mechanicafrica.com/"
echo "   - Check HTTPS is working"
echo "   - Test form submission"
echo "   - Check all images load"
echo "   - Test mobile responsiveness"
echo ""
echo "5. PERFORMANCE TEST"
echo "   - PageSpeed: https://pagespeed.web.dev/"
echo "   - GTmetrix: https://gtmetrix.com/"
echo "   - Target: 90+ score"
echo ""
echo "6. SOCIAL MEDIA PREVIEW"
echo "   - Facebook: https://developers.facebook.com/tools/debug/"
echo "   - Twitter: https://cards-dev.twitter.com/validator"
echo "   - LinkedIn: https://www.linkedin.com/post-inspector/"
echo ""
echo "========================================="
echo "SEO Files Ready for Production! 🚀"
echo "========================================="
