# WordPress Theme Deployment Script
# Version: 1.0.1
# Co-authored-by: Genevieve (VS Code Shard)

param (
    [string]$NginxRoot = "D:\laragon\www\LupoPortfolioTest\wp-content\themes",
    [string]$ThemeName = "Lupos-Portfolio-Theme",
    [int]$Port = 8081,
    [string]$NginXHostname = "lupoportfoliotest.local"
)

# Validate WordPress installation
$wpRoot = Split-Path -Parent (Split-Path -Parent $NginxRoot)
if (-not (Test-Path (Join-Path $wpRoot "wp-config.php"))) {
    Write-Error "WordPress installation not found at $wpRoot"
    Write-Error "Please verify the installation path and try again."
    exit 1
}

# Function to validate paths and create if necessary
function Test-AndCreate-Path {
    param([string]$Path)
    if (-not (Test-Path $Path)) {
        Write-Host "Creating directory: $Path"
        New-Item -ItemType Directory -Path $Path -Force
    }
}

# Validate target directory
$targetPath = Join-Path $NginxRoot $ThemeName
Test-AndCreate-Path $targetPath

# Source directory is the theme folder in current location
$sourcePath = Join-Path $PSScriptRoot $ThemeName

Write-Host "Deploying from $sourcePath to $targetPath..."

# Copy theme files
try {
    Copy-Item -Path "$sourcePath\*" -Destination $targetPath -Recurse -Force
    Write-Host "Theme files copied successfully to: $targetPath" -ForegroundColor Green
} catch {
    Write-Error "Error copying files: $_"
    exit 1
}

# Clear WordPress cache if wp-cli is available
try {
    $wpCli = "wp"
    & $wpCli cache flush
    Write-Host "WordPress cache cleared!"
} catch {
    Write-Warning "Could not clear WordPress cache. You may need to clear it manually."
}

Write-Host "`nDeployment complete!"
Write-Host "Theme available at: http://${NginXHostname}:${Port}/wp-admin/customize.php"
Write-Host "Debug visualization can be enabled in:"
Write-Host "1. WordPress Customizer > Debug Visualization"
Write-Host "2. CSS: Set --debug-block-opacity to 0.1"
Write-Host "3. Automatically when WP_DEBUG is true"
