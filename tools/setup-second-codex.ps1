param(
    [string]$RepoOwner = "umarmalikk885-del",
    [string]$RepoName = "commission_shop1",
    [string]$TargetRoot = "$HOME\Desktop",
    [string]$GitUserName = "",
    [string]$GitUserEmail = ""
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

function Ensure-Tool {
    param(
        [Parameter(Mandatory = $true)]
        [string]$CommandName,
        [Parameter(Mandatory = $true)]
        [string]$WingetId
    )

    $cmd = Get-Command $CommandName -ErrorAction SilentlyContinue
    if ($cmd) {
        return $cmd.Source
    }

    Write-Host "$CommandName not found. Installing with winget..."
    winget install --id $WingetId -e --source winget --accept-package-agreements --accept-source-agreements | Out-Null

    $cmd = Get-Command $CommandName -ErrorAction SilentlyContinue
    if ($cmd) {
        return $cmd.Source
    }

    throw "Failed to install $CommandName. Install it manually and rerun."
}

function Invoke-Native {
    param(
        [Parameter(Mandatory = $true)]
        [string]$FilePath,
        [Parameter(Mandatory = $true)]
        [string[]]$Arguments
    )

    & $FilePath @Arguments
    if ($LASTEXITCODE -ne 0) {
        throw "Command failed: $FilePath $($Arguments -join ' ')"
    }
}

$git = Ensure-Tool -CommandName "git" -WingetId "Git.Git"
$gh = Ensure-Tool -CommandName "gh" -WingetId "GitHub.cli"

Write-Host "Checking GitHub authentication..."
& $gh auth status | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "Starting GitHub login..."
    Invoke-Native -FilePath $gh -Arguments @("auth", "login", "--web", "--git-protocol", "https")
}

$repoUrl = "https://github.com/$RepoOwner/$RepoName.git"
$targetPath = Join-Path $TargetRoot $RepoName

if (-not (Test-Path $TargetRoot)) {
    New-Item -Path $TargetRoot -ItemType Directory | Out-Null
}

if (-not (Test-Path $targetPath)) {
    Write-Host "Cloning repository to $targetPath ..."
    Invoke-Native -FilePath $git -Arguments @("clone", $repoUrl, $targetPath)
} else {
    Write-Host "Repository already exists at $targetPath"
}

Push-Location $targetPath
try {
    if (-not [string]::IsNullOrWhiteSpace($GitUserName)) {
        Invoke-Native -FilePath $git -Arguments @("config", "user.name", $GitUserName)
    }
    if (-not [string]::IsNullOrWhiteSpace($GitUserEmail)) {
        Invoke-Native -FilePath $git -Arguments @("config", "user.email", $GitUserEmail)
    }

    Invoke-Native -FilePath $git -Arguments @("checkout", "main")
    Invoke-Native -FilePath $git -Arguments @("pull", "origin", "main")

    Write-Host ""
    Write-Host "Second Codex setup complete."
    Write-Host "Repo path: $targetPath"
    Write-Host "Next steps:"
    Write-Host "1) Read CONTRIBUTING.md and docs/team/second-codex-guide.md"
    Write-Host "2) Create a branch: git checkout -b feature/<ticket>-<short-name>"
    Write-Host "3) Start work and open PR with template"
}
finally {
    Pop-Location
}
