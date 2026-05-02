# Laravel Test Runner Script
# Run all tests with detailed output

param(
    [switch]$Parallel,
    [switch]$Coverage,
    [switch]$Profile
)

Write-Host "🧪 Running Laravel Tests..." -ForegroundColor Cyan
Write-Host ""

$testCommand = "php artisan test"

if ($Parallel) {
    Write-Host "⚡ Running tests in parallel mode..." -ForegroundColor Yellow
    $testCommand += " --parallel"
}

if ($Coverage) {
    Write-Host "📊 Running with code coverage..." -ForegroundColor Yellow
    $testCommand += " --coverage"
}

if ($Profile) {
    Write-Host "⏱️  Profiling slowest tests..." -ForegroundColor Yellow
    $testCommand += " --profile"
}

# Run tests
Invoke-Expression $testCommand

Write-Host ""
Write-Host "✅ Tests completed!" -ForegroundColor Green
Write-Host ""
Write-Host "Usage examples:" -ForegroundColor Cyan
Write-Host "  .\run-tests.ps1              # Normal tests" -ForegroundColor Gray
Write-Host "  .\run-tests.ps1 -Parallel     # Parallel tests (faster)" -ForegroundColor Gray
Write-Host "  .\run-tests.ps1 -Coverage    # With code coverage" -ForegroundColor Gray
Write-Host "  .\run-tests.ps1 -Profile     # Show slowest tests" -ForegroundColor Gray

