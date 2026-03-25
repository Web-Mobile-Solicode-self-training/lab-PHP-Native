# NativePHP Mobile Manual Binary Downloader
# This script manually downloads and installs PHP 8.4 binaries for Android.

$phpVersion = "8.4"
$nativePhpVersion = "3.1.0"
$phpFullVersion = "8.4.19"
$branch = "main"

# Configuration
$withIcu = $false
$sevenZipPath = "C:\Program Files\7-Zip\7z.exe"

# Derived Paths
$binaryCacheDir = ".\nativephp\binaries"
$tempExtractDir = ".\storage\android-temp"
$destinationDir = ".\nativephp\android\app\src\main"

$icuSuffix = ""
if ($withIcu) {
    $icuSuffix = "-icu"
}

$zipName = "android-$nativePhpVersion-php$phpFullVersion$icuSuffix.zip"
$url = "https://bin.nativephp.com/$branch/$phpVersion/android/$zipName"

Write-Host "--- NativePHP Manual Binary Downloader ---" -ForegroundColor Cyan
Write-Host "PHP Version: $phpVersion ($phpFullVersion)"
Write-Host "ICU Support: $(if($withIcu){'Enabled'}else{'Disabled'})"
Write-Host "URL: $url"
Write-Host ""

# 1. Ensure directories exist
if (!(Test-Path $binaryCacheDir)) {
    Write-Host "Creating cache directory: $binaryCacheDir"
    New-Item -ItemType Directory -Path $binaryCacheDir -Force | Out-Null
}

if (!(Test-Path $tempExtractDir)) {
    Write-Host "Creating temp directory: $tempExtractDir"
    New-Item -ItemType Directory -Path $tempExtractDir -Force | Out-Null
} else {
    Write-Host "Cleaning existing temp directory..."
    Remove-Item -Path "$tempExtractDir\*" -Recurse -Force -ErrorAction SilentlyContinue
}

# 2. Check for 7-Zip
if (!(Test-Path $sevenZipPath)) {
    Write-Error "7-Zip not found at $sevenZipPath. Please install 7-Zip or update the script with the correct path."
    exit 1
}

# 3. Download the binary
$zipFile = Join-Path $binaryCacheDir $zipName
if (!(Test-Path $zipFile)) {
    Write-Host "Downloading binaries... (this may take a minute)" -ForegroundColor Yellow
    try {
        Invoke-WebRequest -Uri $url -OutFile $zipFile -ErrorAction Stop
        Write-Host "Download complete: $zipName" -ForegroundColor Green
    } catch {
        Write-Error "Failed to download binaries from $url. Please check your internet connection."
        exit 1
    }
} else {
    Write-Host "Using cached binary: $zipName" -ForegroundColor Green
}

# 4. Extract binaries
Write-Host "Extracting binaries..." -ForegroundColor Yellow
$outDirArg = "-o" + $tempExtractDir
& $sevenZipPath x $zipFile $outDirArg -y | Out-Null

if ($LASTEXITCODE -ne 0) {
    Write-Error "7-Zip extraction failed."
    exit 1
}
Write-Host "Extraction complete." -ForegroundColor Green

# 5. Copy to destination
Write-Host "Installing libraries to $destinationDir..." -ForegroundColor Yellow
if (!(Test-Path $destinationDir)) {
    New-Item -ItemType Directory -Path $destinationDir -Force | Out-Null
}

# Use Copy-Item with -Recurse and -Force
Copy-Item -Path "$tempExtractDir\*" -Destination $destinationDir -Recurse -Force

Write-Host "Installation complete!" -ForegroundColor Green

# 6. Cleanup
Write-Host "Cleaning up temporary files..."
Remove-Item -Path $tempExtractDir -Recurse -Force -ErrorAction SilentlyContinue

Write-Host ""
Write-Host "You can now run 'php artisan native:install' again with the '--skip-php' flag to complete the setup." -ForegroundColor Cyan
