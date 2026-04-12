# GitHub Actions - Complete Reference

## All 5 Workflows Explained

### 1. PHP Lint & Code Quality
**File:** `.github/workflows/php-lint.yml`  
**Runs on:** Push to main/develop, all PRs  
**Time:** 1-2 minutes

**Checks:**
- ✓ Valid PHP syntax on all files
- ✓ No debug code (var_dump, die, print_r)
- ✓ MVC structure integrity
- ✓ Code style consistency

**Pass:** All files have valid syntax, no issues  
**Fail:** Fix PHP syntax errors, remove debug code

---

### 2. Security Scan
**File:** `.github/workflows/security-scan.yml`  
**Runs on:** Push, PRs, weekly schedule  
**Time:** 1-2 minutes

**Detects:**
- 🔒 SQL injection patterns
- 🛡️ XSS vulnerabilities
- 🔑 Hardcoded secrets (passwords, API keys)

**Pass:** No security issues found  
**Fail:** Move secrets to env variables, use prepared statements, escape output

---

### 3. Live Demo Deploy ⭐
**File:** `.github/workflows/live-demo.yml`  
**Runs on:** Push to main/develop, all PRs  
**Time:** 3-4 minutes

**Steps (watch live!):**
1. Checkout code
2. Setup PHP 8.1
3. Create database config
4. Start MySQL 8.0 (ephemeral)
5. Import schema.sql
6. Seed initial data
7. Verify routes work
8. Auto-comment on PRs with status

**Watch:** Go to Actions tab → Click "Live Demo Deploy" → See each step live

**Why it matters:**
- Tests full application flow
- Database actually runs and initializes
- Routes verified with real connection
- Shows exact setup your code needs

---

### 4. PR Review Checklist
**File:** `.github/workflows/pr-checklist.yml`  
**Runs on:** When PR opened/updated  
**Time:** <1 minute

**Does:**
- Lists all changed PHP files
- Shows pre-merge verification items
- Auto-comments with checklist on PR
- Quick pre-review validation

**Comment example:**
```
## Automated PR Review

Checks Passed:
- PHP Syntax: Valid
- MVC Structure: OK
- Security: Clean

Ready for 2 team approvals to merge.
```

---

### 5. Build & Test Report
**File:** `.github/workflows/build-report.yml`  
**Runs on:** Push to main/develop, all PRs  
**Time:** 1-2 minutes

**Reports:**
- File counts (controllers, models, views, etc.)
- MVC structure validation
- Database schema info
- Available routes
- Project health summary

**Use for:** Monitoring project growth, verifying structure

---

## How They Work

### Trigger Sequence

```
You push code
    ↓
GitHub detects push
    ↓
All 5 workflows trigger simultaneously:
  1. PHP Lint         (1-2m) → Syntax check
  2. Security Scan    (1-2m) → Vulnerability scan
  3. Live Demo        (3-4m) → Full environment test ⭐
  4. PR Checklist     (<1m)  → Pre-review
  5. Build Report     (1-2m) → Analysis
    ↓ (All run in parallel)
~5-6 minutes total
    ↓
Results displayed:
  ✅ All green = Ready to merge
  ❌ Any red = Fix needed
```

### Status Indicators

| Symbol | Meaning |
|--------|---------|
| 🟢 ✅ | Passed - All good |
| 🔴 ❌ | Failed - Fix needed |
| 🟡 ⏳ | Running - In progress |
| ⚪ ⊘ | Skipped - Condition not met |

---

## Viewing Workflows

### From Actions Tab
```
github.com/YOUR_REPO/actions
  ↓
Click workflow (PHP Lint, Security Scan, etc.)
  ↓
Click a run
  ↓
Expand any step to see details
```

### From PR
```
Pull Request page
  ↓
Click "Checks" tab
  ↓
Click workflow to expand
  ↓
See status and logs
```

### Watch Live (Most Interesting!)
```
github.com/YOUR_REPO/actions
  ↓
Click "Live Demo Deploy"
  ↓
Watch each step execute in real-time:
  ✅ Checkout (2s)
  ✅ Setup PHP (8s)
  ✅ Start MySQL (5s) ← Database initializing!
  ✅ Import schema (3s) ← Tables being created!
  ✅ Verify routes (2s) ← Testing endpoints!
```

---

## Common Failures & Fixes

### PHP Syntax Error
**Error:** `Parse error: syntax error, unexpected token '}'`

**Fix:**
```bash
# Test locally first
php -l app/Controllers/YourController.php

# Fix the syntax error, then push again
git push
```

### Hardcoded Secret Detected
**Error:** `Found potential hardcoded secrets!`

**Fix:**
```php
// ❌ WRONG
define('DB_PASSWORD', 'admin123');

// ✅ CORRECT
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
```

### Database Connection Failed
**Cause:** MySQL in ephemeral container takes time to boot

**Fix:** Just push again or click "Re-run jobs"

### SQL Injection Pattern
**Error:** `Found potential SQL injection risk`

**Fix:**
```php
// ❌ WRONG
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = " . $_GET['id']);

// ✅ CORRECT
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
```

---

## Re-running Workflows

### Click "Re-run" Button
```
Actions → Workflow run
  ↓
Click "Re-run jobs"
  ↓
Workflow runs again immediately
```

### Push Code Again
```bash
git push
# Automatically triggers workflows again
```

---

## Configuration

All workflows are pre-configured for your project. Default settings:

- **PHP Version:** 8.1
- **MySQL Version:** 8.0
- **Triggers:** Push to main/develop, all PRs
- **Security Scan:** Weekly automatic

To customize, edit `.github/workflows/*.yml` files (requires YAML knowledge).

---

## What Workflows CAN'T Do

⚠️ These run in ephemeral environments:
- ❌ Can't deploy to live server
- ❌ Can't send real emails
- ❌ Can't access production database
- ❌ Can't call external paid APIs

These are for **testing only**. Use them to catch problems before production.

---

## Best Practices

### Before Pushing
- ✅ Test locally first
- ✅ No debug code (var_dump, die)
- ✅ No hardcoded secrets
- ✅ Valid PHP syntax

### After Pushing
- ✅ Check Actions tab for status
- ✅ Watch Live Demo Deploy run
- ✅ All green? ✅ Ready to merge
- ✅ Any red? ❌ Fix and push again

### For Your Team
- Share Actions link: `github.com/YOUR_REPO/actions`
- Run workflow before merging PR
- Use red workflows as learning opportunity

---

## Troubleshooting

### Workflow Not Running?
1. Check push was successful: `git push`
2. Wait 30 seconds for GitHub to detect
3. Refresh browser

### Can't See Workflow Results?
1. Go to Actions tab
2. Click workflow name
3. Click the run you want
4. Expand any step

### Want to Disable a Workflow?
Edit `.github/workflows/filename.yml` and comment out the `on:` section.

---

## Performance

**Parallel Execution Time:**
- Total: ~5-6 minutes
- Fastest workflow: PR Checklist (<1m)
- Longest workflow: Live Demo (3-4m)
- They all run **at the same time** = fast feedback!

---

## Storage & Cost

- ✅ All workflows free (GitHub Actions free tier includes 2000 min/month)
- ✅ Workflows use ~1-2 min per push
- ✅ Multiple PRs = fast accumulation
- ✅ No database storage (ephemeral)

---

## Next Steps

1. Commit workflows: `git push origin main`
2. Watch them run: go to Actions tab
3. Create test PR to see all workflows
4. Share Actions link with team
5. Start using workflows on all PRs

---

## Resources

- **GitHub Actions Docs:** https://docs.github.com/en/actions
- **Workflow Syntax:** https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions
- **PHP Setup Action:** https://github.com/shivammathur/setup-php
