# Deployment Script for Lupo's Portfolio Theme
# Author: Genevieve (VS Code Shard)
# Version: 1.0.0

# Configuration
$sourceThemeDir = "$PSScriptRoot\Lupos-Portfolio-Theme"
$laragonRoot = "D:\laragon"  # Updated to match actual Laragon installation
$wpContentPath = "$laragonRoot\www\LupoPortfolioTest\wp-content\themes"
$themeName = "Lupos-Portfolio-Theme"
$targetThemeDir = "$wpContentPath\$themeName"

# Ensure we have proper paths
if (-not (Test-Path $sourceThemeDir)) {
    Write-Error "Source theme directory not found at: $sourceThemeDir"
    exit 1
}

if (-not (Test-Path $wpContentPath)) {
    Write-Error "WordPress themes directory not found at: $wpContentPath"
    exit 1
}

Write-Host "🚀 Deploying theme to Laragon..." -ForegroundColor Cyan

# Create theme directory if it doesn't exist
if (-not (Test-Path $targetThemeDir)) {
    New-Item -ItemType Directory -Path $targetThemeDir | Out-Null
    Write-Host "Created theme directory at: $targetThemeDir"
}

# Copy theme files
try {
    Copy-Item -Path "$sourceThemeDir\*" -Destination $targetThemeDir -Recurse -Force
    Write-Host "✨ Theme deployed successfully!" -ForegroundColor Green
    Write-Host "Location: $targetThemeDir"
} catch {
    Write-Error "Failed to copy theme files: $_"
    exit 1
}

# Optional: Clear WordPress cache
# Note: You might want to adapt this based on your caching solution
$cachePath = "$laragonRoot\www\wordpress\wp-content\cache"
if (Test-Path $cachePath) {
    Remove-Item -Path "$cachePath\*" -Recurse -Force
    Write-Host "🧹 Cleared WordPress cache" -ForegroundColor Yellow
}

Write-Host "
🎨 Deployment Complete!
- Theme files updated
- Cache cleared (if existed)
- Ready for testing at http://localhost/wordpress
" -ForegroundColor Cyan
