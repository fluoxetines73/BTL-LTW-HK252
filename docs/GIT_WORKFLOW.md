# Git Feature Branch Workflow

## Quick Reference

```bash
# Start feature
git checkout develop
git pull origin develop
git checkout -b feature/your-feature-name

# Work on feature
git add .
git commit -m "feat: add new feature"

# Keep updated
git fetch origin
git rebase origin/develop

# Push & PR
git push -u origin feature/your-feature-name

# On GitHub: Open PR
# - Get 2 approvals
# - Wait for all checks ✅
# - Click "Merge"

# Cleanup
git branch -d feature/your-feature-name
```

---

## Branch Naming Convention

### Format: `{type}/{name}`

```
feature/user-authentication
feature/product-search
bugfix/login-validation-error
hotfix/payment-crash
docs/api-documentation
```

**Rules:**
- `feature/` for new features
- `bugfix/` for bug fixes
- `hotfix/` for emergency production fixes (rare)
- `docs/` for documentation
- Use kebab-case (lowercase, hyphens, no spaces)
- Be descriptive, not vague

---

## Complete Workflow Steps

### Step 1: Create Feature Branch
```bash
# Update develop first
git checkout develop
git pull origin develop

# Create feature branch
git checkout -b feature/amazing-feature
```

### Step 2: Make Changes & Commit
```bash
# Edit files...

# Stage changes
git add app/Controllers/YourController.php
git add app/Views/your-view.php

# Commit with good message
git commit -m "feat(product): add search functionality"

# Format: type(scope): description
# Types: feat, fix, docs, style, refactor, test, chore
```

### Step 3: Keep Updated
```bash
# Fetch latest from remote
git fetch origin

# Rebase your changes on latest develop
git rebase origin/develop

# If conflicts, resolve in editor then:
git add .
git rebase --continue
```

### Step 4: Push to Remote
```bash
git push -u origin feature/amazing-feature
# Use -u flag first time to set upstream
```

### Step 5: Create Pull Request
1. Go to GitHub repo
2. Click "Compare & pull request"
3. Fill in title & description:
   ```
   ## Description
   Brief description of changes

   ## Changes
   - Change 1
   - Change 2

   ## Related Issues
   Closes #123
   ```
4. Request 2+ reviewers
5. Wait for GitHub Actions ✅

### Step 6: Address Feedback
```bash
# If reviewers request changes:
git add .
git commit -m "refactor: address review feedback"
git push  # Automatically updates PR
```

### Step 7: Merge
```bash
# After approval, either:

# Option A: Click "Merge" on GitHub (recommended)
# - Use "Squash and merge" to keep history clean
# - Automatically deletes branch

# Option B: Merge locally
git checkout develop
git pull origin develop
git merge --no-ff feature/amazing-feature
git push origin develop
git branch -d feature/amazing-feature
git push origin --delete feature/amazing-feature
```

---

## Commit Message Format

### Good Commit Messages

```
feat(auth): implement JWT token refresh

This allows users to stay logged in longer
without re-entering credentials every hour.

Resolves #42
```

```
fix(product): resolve image loading timeout

Use async image loading instead of blocking
the product list render.

Fixes #89
```

```
docs: update database setup instructions
```

### Formula
```
{type}({scope}): {description}

{optional body}

{optional footer: Resolves/Fixes #123}
```

**Types:**
- `feat` - New feature
- `fix` - Bug fix
- `docs` - Documentation
- `style` - Formatting (not code logic)
- `refactor` - Code reorganization
- `test` - Test additions
- `chore` - Maintenance, dependencies

---

## Code Review Standards

### Before Requesting Review
- ✅ Syntax validated locally
- ✅ All changes tested
- ✅ No debug code (var_dump, die)
- ✅ No hardcoded secrets
- ✅ Follows MVC conventions
- ✅ Database schema updated (if needed)
- ✅ GitHub Actions all ✅ green

### What Reviewers Check
1. Code logic and efficiency
2. Security (SQL injection, XSS, etc.)
3. MVC conventions followed
4. Database queries optimized
5. No duplicate code
6. Tests added (if applicable)

### Approval Process
- 👍 2+ approvals required
- ❌ 0 rejections allowed (fix feedback, re-request)
- ⏱️ Aim for 24-hour turnaround

---

## Common Scenarios

### Scenario 1: Need to Switch Branches Temporarily
```bash
# Save current work
git stash

# Switch branches
git checkout feature/other-feature

# Later, restore work
git checkout feature/my-feature
git stash pop
```

### Scenario 2: Accidental Commit, Not Pushed Yet
```bash
# Fix the commit
git commit --amend

# Update message if needed, save
```

### Scenario 3: Merge Conflicts During Rebase
```bash
# Git will mark conflicts in files
# Edit files and resolve manually

# Mark as resolved
git add resolved-file.php

# Continue rebase
git rebase --continue
```

### Scenario 4: Discard Local Changes
```bash
# Discard single file
git checkout -- app/Controllers/MyController.php

# Discard all changes
git reset --hard origin/develop
```

### Scenario 5: See What's Different from Develop
```bash
# Show commits only in your branch
git log develop..feature/my-branch

# Show all changes
git diff develop...feature/my-branch
```

### Scenario 6: Delete Old Branches
```bash
# Delete local
git branch -d feature/old-feature

# Delete remote
git push origin --delete feature/old-feature

# List all remote branches
git branch -r
```

---

## Daily Workflow Checklist

### Start of Day
```bash
git checkout develop
git pull origin develop
git checkout feature/your-branch
git rebase origin/develop
```

### Before Pushing
```bash
git status              # Review changes
git diff               # Review code
git log --oneline -5   # Review commits
```

### End of Day
```bash
git push               # Back up work
```

---

## Team Rules

### ❌ Never
- Force-push to `main` or `develop`
- Commit directly to `main` or `develop`
- Merge without code review
- Leave debug code in commits
- Commit hardcoded secrets

### ✅ Always
- Use feature branches
- Create pull requests
- Request code reviews
- Wait for GitHub Actions ✅
- Use meaningful commit messages
- Update branch before merging

---

## Release to Production (main)

Only tech lead merges to `main`:

```bash
# Test everything on develop first
# Then prepare release:

git checkout main
git pull origin main
git merge --no-ff develop --message "Release v1.x.x"
git tag -a v1.x.x -m "Version 1.x.x"
git push origin main
git push origin v1.x.x
```

### Release Checklist
- [ ] All develop features tested
- [ ] Version number updated
- [ ] Database migrations documented
- [ ] No breaking changes
- [ ] All tests passing
- [ ] Code reviewed & approved

---

## Troubleshooting

### "Your branch has diverged"
```bash
git rebase origin/develop
git push --force-with-lease
```

### "Permission denied on push"
```bash
# Check SSH keys
ssh -T git@github.com

# Or use HTTPS instead of SSH
```

### "Can't find remote tracking branch"
```bash
git branch -u origin/feature/my-branch
```

### "Merge conflict markers in file"
```bash
# Edit file, remove <<<<<<, ======, >>>>>>
# Keep the code you want
git add file.php
git rebase --continue  # or git merge --continue
```

### "Accidentally committed to develop"
```bash
# Contact tech lead immediately!
# Don't force-push, we'll fix it together
```

---

## Git Configuration (One-Time)

```bash
git config --global user.name "Your Name"
git config --global user.email "your@email.com"
git config --global pull.rebase false
git config --global init.defaultBranch main
```

---

## Tools

### View History
```bash
git log --oneline --graph --all
# Shows all branches visually
```

### Find When Something Broke
```bash
git bisect start
# Binary search through commits
```

### Search for Code in History
```bash
git log -S "function_name"
# Shows commits that added/removed this
```

---

## Next Steps

1. **First time setup:**
   - Clone repo
   - Configure git (see Configuration section)
   - Create feature branch

2. **Start coding:**
   - Make changes
   - Commit regularly
   - Keep branch updated
   - Push to remote

3. **Submit PR:**
   - Request reviewers
   - Wait for checks & approvals
   - Merge to develop

4. **Done!**
   - Delete feature branch
   - Delete local copy
   - Start next feature

---

## Quick Links

- **GitHub:** https://github.com/fluoxetines73/BTL-LTW-HK252
- **This Project:** C:\Users\tdanh\Desktop\BTL-LTW-HK252
- **Issues:** Check GitHub Issues tab
- **PRs:** Check GitHub Pull Requests tab
