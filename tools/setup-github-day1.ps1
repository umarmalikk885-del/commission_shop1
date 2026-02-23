param(
    [Parameter(Mandatory = $true)]
    [string]$RepoOwner,

    [Parameter(Mandatory = $true)]
    [string]$RepoName,

    [ValidateSet("private", "public", "internal")]
    [string]$Visibility = "private"
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

function Get-Executable {
    param(
        [Parameter(Mandatory = $true)]
        [string]$Name,
        [Parameter(Mandatory = $true)]
        [string]$FallbackPath
    )

    $cmd = Get-Command $Name -ErrorAction SilentlyContinue
    if ($cmd) {
        return $cmd.Source
    }

    if (Test-Path $FallbackPath) {
        return $FallbackPath
    }

    throw "Unable to locate executable: $Name"
}

$git = Get-Executable -Name "git" -FallbackPath "C:\Program Files\Git\cmd\git.exe"
$gh = Get-Executable -Name "gh" -FallbackPath "C:\Program Files\GitHub CLI\gh.exe"
$repoSlug = "$RepoOwner/$RepoName"

Write-Host "Checking GitHub authentication..."
& $gh auth status | Out-Null

Write-Host "Ensuring local git repository exists..."
try {
    & $git rev-parse --is-inside-work-tree | Out-Null
} catch {
    & $git init -b main | Out-Null
}

$currentBranch = (& $git branch --show-current).Trim()
if ([string]::IsNullOrWhiteSpace($currentBranch)) {
    & $git checkout -b main | Out-Null
} elseif ($currentBranch -ne "main") {
    & $git checkout main | Out-Null
}

Write-Host "Checking repository on GitHub: $repoSlug"
$repoExists = $true
try {
    & $gh repo view $repoSlug | Out-Null
} catch {
    $repoExists = $false
}

if (-not $repoExists) {
    Write-Host "Creating repository on GitHub..."
    & $gh repo create $repoSlug --$Visibility --source . --remote origin | Out-Null
}

$originUrl = ""
try {
    $originUrl = (& $git remote get-url origin).Trim()
} catch {
    $originUrl = ""
}

if ([string]::IsNullOrWhiteSpace($originUrl)) {
    $remoteUrl = "https://github.com/$repoSlug.git"
    Write-Host "Adding origin remote: $remoteUrl"
    & $git remote add origin $remoteUrl
}

Write-Host "Pushing main branch..."
& $git push -u origin main

Write-Host "Configuring repository merge options..."
& $gh api `
    --method PATCH `
    -H "Accept: application/vnd.github+json" `
    "/repos/$repoSlug" `
    -f name="$RepoName" `
    -f allow_squash_merge=true `
    -f allow_merge_commit=false `
    -f allow_rebase_merge=false `
    -f allow_auto_merge=false `
    -f delete_branch_on_merge=false | Out-Null

Write-Host "Creating/updating labels..."
& $gh label create "priority:high" --repo $repoSlug --color "B60205" --description "High priority" --force | Out-Null
& $gh label create "priority:medium" --repo $repoSlug --color "FBCA04" --description "Medium priority" --force | Out-Null
& $gh label create "priority:low" --repo $repoSlug --color "0E8A16" --description "Low priority" --force | Out-Null
& $gh label create "risk:high" --repo $repoSlug --color "D93F0B" --description "High risk change" --force | Out-Null
& $gh label create "risk:low" --repo $repoSlug --color "1D76DB" --description "Low risk change" --force | Out-Null
& $gh label create "needs-tests" --repo $repoSlug --color "5319E7" --description "Tests are missing or insufficient" --force | Out-Null
& $gh label create "ready-for-review" --repo $repoSlug --color "0052CC" --description "Ready for reviewer assignment" --force | Out-Null
& $gh label create "ready-to-merge" --repo $repoSlug --color "0E8A16" --description "All checks passed and approved" --force | Out-Null

Write-Host "Applying branch protection on main..."
$protectionPayload = @{
    required_status_checks = @{
        strict   = $true
        contexts = @(
            "php-lint",
            "php-tests",
            "frontend-build",
            "branch-name-check",
            "commit-message-check",
            "pr-template-check"
        )
    }
    enforce_admins                      = $true
    required_pull_request_reviews       = @{
        dismiss_stale_reviews           = $true
        require_code_owner_reviews      = $false
        required_approving_review_count = 1
    }
    restrictions                        = $null
    required_linear_history             = $true
    allow_force_pushes                  = $false
    allow_deletions                     = $false
    block_creations                     = $false
    required_conversation_resolution    = $true
    lock_branch                         = $false
}

$json = $protectionPayload | ConvertTo-Json -Depth 10 -Compress
$json | & $gh api `
    --method PUT `
    -H "Accept: application/vnd.github+json" `
    "/repos/$repoSlug/branches/main/protection" `
    --input -

Write-Host ""
Write-Host "Day-1 setup complete for $repoSlug"
Write-Host "Next:"
Write-Host "1) Add Developer A, Developer B, and Integrator as collaborators."
Write-Host "2) Ensure required checks are visible in branch protection after first PR run."
Write-Host "3) Start Day 2 drill from docs/team/7-day-bootcamp.md"
