#!/bin/bash

# File Cleanup Script for Production Server
# Removes unnecessary files that are now blocked by .htaccess
# These files are kept local but should be removed from production

echo "============================================="
echo "🗑️  Mechanic Africa - File Cleanup"
echo "============================================="
echo ""
echo "This script will DELETE the following files:"
echo ""
echo "Admin Setup Scripts (Blocked by .htaccess):"
echo "  - init-database.php"
echo "  - create-super-admin.php"
echo "  - add-superadmin.php"
echo ""
echo "Backup Files:"
echo "  - index-backup-20251112-182813.php"
echo "  - index-old-20251112-183447.html"
echo "  - mechanic-africa-v2 (1).html"
echo ""
echo "Test Files:"
echo "  - test-form.html"
echo ""
echo "⚠️  WARNING: This action CANNOT be undone!"
echo "These files are already blocked by .htaccess but removing them adds extra security."
echo ""

read -p "Are you sure you want to delete these files? (yes/no): " CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo "Cleanup cancelled. No files were deleted."
    exit 0
fi

echo ""
echo "Starting cleanup..."
echo ""

# Function to delete file if it exists
delete_if_exists() {
    if [ -f "$1" ]; then
        rm "$1"
        echo "✅ Deleted: $1"
    else
        echo "⏭️  Skip: $1 (not found)"
    fi
}

# Admin setup scripts
delete_if_exists "init-database.php"
delete_if_exists "create-super-admin.php"
delete_if_exists "add-superadmin.php"

# Backup files
delete_if_exists "index-backup-20251112-182813.php"
delete_if_exists "index-old-20251112-183447.html"
delete_if_exists "mechanic-africa-v2 (1).html"

# Test files
delete_if_exists "test-form.html"

echo ""
echo "============================================="
echo "✅ Cleanup Complete!"
echo "============================================="
echo ""
echo "Files remaining:"
ls -lh *.php *.html 2>/dev/null | grep -v "total" || echo "No PHP/HTML files found"
echo ""
echo "Note: These files are still in your Git repository."
echo "They were only deleted from the current directory."
echo ""
echo "To prevent them from being deployed in future:"
echo "1. Add to .gitignore (if desired)"
echo "2. Or keep in repo for local development only"
