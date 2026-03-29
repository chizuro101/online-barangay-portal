# PowerShell script to run SQL schema update
$mysqlPath = "C:\xampp\mysql\bin\mysql.exe"
$database = "online-barangay-portal"
$sqlFile = "update_announcement_schema.sql"

Write-Host "Running database schema update..."
Write-Host "Please enter your MySQL password when prompted."

# Check if MySQL exists
if (-not (Test-Path $mysqlPath)) {
    Write-Host "MySQL not found at $mysqlPath"
    Write-Host "Please ensure XAMPP MySQL is installed and the path is correct."
    exit 1
}

# Read SQL file and execute
$sqlContent = Get-Content $sqlFile | Out-String
& $mysqlPath -u root -p $database -e $sqlContent

if ($LASTEXITCODE -eq 0) {
    Write-Host "Schema update completed successfully!"
} else {
    Write-Host "Error running schema update. Please check your MySQL credentials and database name."
}
