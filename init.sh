#!/bin/bash
# PHP SDK Template Initializer
# Usage: ./init.sh
#
# This script will:
# 1. Collect project information
# 2. Replace all template variables
# 3. Optionally remove Laravel support files
# 4. Clean up template files
# 5. Initialize a new Git repository

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Banner
echo -e "${BLUE}"
echo "╔═══════════════════════════════════════════════════════════╗"
echo "║                                                           ║"
echo "║       🚀 PHP API Client SDK Template Initializer 🚀       ║"
echo "║                                                           ║"
echo "║                   by CarlLee1983                          ║"
echo "║                                                           ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Check if running from template directory
if [ ! -f "composer.json" ]; then
    echo -e "${RED}Error: Please run this script from the template root directory.${NC}"
    exit 1
fi

# Collect information
echo -e "${CYAN}Please provide the following information:${NC}"
echo ""

read -p "📦 Package name (e.g., carllee1983/my-sdk): " PACKAGE_NAME
if [ -z "$PACKAGE_NAME" ]; then
    echo -e "${RED}Error: Package name is required.${NC}"
    exit 1
fi

read -p "📝 Package description: " PACKAGE_DESC
if [ -z "$PACKAGE_DESC" ]; then
    echo -e "${RED}Error: Package description is required.${NC}"
    exit 1
fi

read -p "🏷️  PHP namespace (e.g., MyCompany\\MySdk): " NAMESPACE
if [ -z "$NAMESPACE" ]; then
    echo -e "${RED}Error: Namespace is required.${NC}"
    exit 1
fi

read -p "👤 GitHub username [CarlLee1983]: " REPO_OWNER
REPO_OWNER=${REPO_OWNER:-CarlLee1983}

read -p "📁 Repository name: " REPO_NAME
if [ -z "$REPO_NAME" ]; then
    echo -e "${RED}Error: Repository name is required.${NC}"
    exit 1
fi

read -p "✍️  Author name [Carl Lee]: " AUTHOR_NAME
AUTHOR_NAME=${AUTHOR_NAME:-Carl Lee}

read -p "📧 Author email [carllee1983@gmail.com]: " AUTHOR_EMAIL
AUTHOR_EMAIL=${AUTHOR_EMAIL:-carllee1983@gmail.com}

read -p "🎯 Include Laravel support? (y/n) [y]: " INCLUDE_LARAVEL
INCLUDE_LARAVEL=${INCLUDE_LARAVEL:-y}

# Calculate derived variables
# Convert namespace to path (MyCompany\MySdk -> MyCompany/MySdk)
NAMESPACE_PATH=$(echo "$NAMESPACE" | sed 's/\\/\//g')

# Escape backslashes for JSON (MyCompany\MySdk -> MyCompany\\MySdk)
NAMESPACE_ESCAPED=$(echo "$NAMESPACE" | sed 's/\\/\\\\/g')

# Double escape for sed replacement
NAMESPACE_DOUBLE_ESCAPED=$(echo "$NAMESPACE" | sed 's/\\/\\\\\\\\/g')

# Get current year
YEAR=$(date +%Y)

# Confirmation
echo ""
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${YELLOW}Configuration Summary:${NC}"
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo -e "  📦 Package:     ${GREEN}$PACKAGE_NAME${NC}"
echo -e "  📝 Description: ${GREEN}$PACKAGE_DESC${NC}"
echo -e "  🏷️  Namespace:   ${GREEN}$NAMESPACE${NC}"
echo -e "  📁 Repository:  ${GREEN}https://github.com/$REPO_OWNER/$REPO_NAME${NC}"
echo -e "  ✍️  Author:      ${GREEN}$AUTHOR_NAME <$AUTHOR_EMAIL>${NC}"
echo -e "  🎯 Laravel:     ${GREEN}$INCLUDE_LARAVEL${NC}"
echo ""
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

read -p "Proceed with initialization? (y/n): " CONFIRM
if [ "$CONFIRM" != "y" ]; then
    echo -e "${RED}Aborted.${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}🔧 Initializing...${NC}"
echo ""

# Function to replace in files (macOS compatible)
replace_in_file() {
    local file=$1
    
    # Skip binary files and this script
    if [ "$file" = "./init.sh" ]; then
        return
    fi
    
    # Use perl for more reliable replacement (handles special chars better)
    perl -i -pe "s/\{\{PACKAGE_NAME\}\}/$PACKAGE_NAME/g" "$file" 2>/dev/null || true
    perl -i -pe "s/\{\{PACKAGE_DESCRIPTION\}\}/$PACKAGE_DESC/g" "$file" 2>/dev/null || true
    perl -i -pe "s/\{\{NAMESPACE\}\}/$NAMESPACE/g" "$file" 2>/dev/null || true
    perl -i -pe "s/\{\{NAMESPACE_ESCAPED\}\}/$NAMESPACE_ESCAPED/g" "$file" 2>/dev/null || true
    perl -i -pe "s/\{\{NAMESPACE_PATH\}\}/$NAMESPACE_PATH/g" "$file" 2>/dev/null || true
    perl -i -pe "s/\{\{REPO_OWNER\}\}/$REPO_OWNER/g" "$file" 2>/dev/null || true
    perl -i -pe "s/\{\{REPO_NAME\}\}/$REPO_NAME/g" "$file" 2>/dev/null || true
    perl -i -pe "s/\{\{AUTHOR_NAME\}\}/$AUTHOR_NAME/g" "$file" 2>/dev/null || true
    perl -i -pe "s/\{\{AUTHOR_EMAIL\}\}/$AUTHOR_EMAIL/g" "$file" 2>/dev/null || true
    perl -i -pe "s/\{\{YEAR\}\}/$YEAR/g" "$file" 2>/dev/null || true
}

# Find and process all text files
echo -e "  📝 Replacing template variables..."
find . -type f \( \
    -name "*.php" -o \
    -name "*.md" -o \
    -name "*.json" -o \
    -name "*.xml" -o \
    -name "*.yml" -o \
    -name "*.yaml" \
    \) \
    -not -path "./vendor/*" \
    -not -path "./.git/*" \
    -not -name "init.sh" \
    | while read file; do
        replace_in_file "$file"
    done

echo -e "  ${GREEN}✓${NC} Template variables replaced"

# Remove Laravel support if not needed
if [ "$INCLUDE_LARAVEL" != "y" ]; then
    echo -e "  🗑️  Removing Laravel support files..."
    rm -rf src/Laravel
    rm -rf config
    
    # Remove Laravel extra from composer.json
    # This is a simplified removal - user may need to manually clean up
    perl -i -pe 'BEGIN{undef $/;} s/,?\s*"extra"\s*:\s*\{[^}]*"laravel"[^}]*\}[^}]*\}//sg' composer.json 2>/dev/null || true
    
    echo -e "  ${GREEN}✓${NC} Laravel support removed"
fi

# Clean up template files
echo -e "  🗑️  Cleaning up template files..."
rm -f init.sh
echo -e "  ${GREEN}✓${NC} Template files cleaned"

# Initialize new Git repository
echo -e "  📦 Initializing Git repository..."
rm -rf .git
git init -q
git add .
echo -e "  ${GREEN}✓${NC} Git repository initialized"

# Success message
echo ""
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}✅ Initialization complete!${NC}"
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo -e "${CYAN}Next steps:${NC}"
echo ""
echo -e "  1. Review the generated files"
echo ""
echo -e "  2. Install dependencies:"
echo -e "     ${YELLOW}composer install${NC}"
echo ""
echo -e "  3. Run tests:"
echo -e "     ${YELLOW}composer test${NC}"
echo ""
echo -e "  4. Create initial commit:"
echo -e "     ${YELLOW}git commit -m 'feat: initial commit'${NC}"
echo ""
echo -e "  5. Add remote and push:"
echo -e "     ${YELLOW}git remote add origin git@github.com:$REPO_OWNER/$REPO_NAME.git${NC}"
echo -e "     ${YELLOW}git push -u origin main${NC}"
echo ""
echo -e "  6. Submit to Packagist:"
echo -e "     ${YELLOW}https://packagist.org/packages/submit${NC}"
echo ""
echo -e "${GREEN}Happy coding! 🎉${NC}"
echo ""
