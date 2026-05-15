# Setup Guide: Comment System

## Overview

A complete comment management system has been added to the news detail page with the following features:

- **User Comments**: Authenticated users can comment on news articles
- **Moderation**: Comments require admin approval before display
- **Reporting**: Users can report inappropriate comments
- **Admin Management**: Full comment management dashboard

## Installation Steps

### 1. Create Database Table

Run this SQL migration to create the comments table:

```sql
-- File: database/migrate_add_comments_table.sql

CREATE TABLE IF NOT EXISTS comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    news_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    is_approved TINYINT(1) DEFAULT 0,
    is_reported TINYINT(1) DEFAULT 0,
    report_reason VARCHAR(255) DEFAULT NULL,
    report_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_news_id (news_id),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    INDEX idx_is_approved (is_approved),
    UNIQUE KEY unique_comment (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Run in MySQL:

```bash
mysql -u root -p cgv_booking < database/migrate_add_comments_table.sql
```

### 2. Files Added

#### Backend

- **app/Models/Comment.php** - Comment data model with CRUD operations
- **app/Controllers/CommentController.php** - Public API for comments
- **app/Controllers/AdminCommentController.php** - Admin management endpoints

#### Frontend

- **app/Views/news/detail.php** - Enhanced with comments section
- **app/Views/admin/comments/index.php** - Admin comment management page
- **public/css/news-detail.css** - Comments styling (already updated)
- **public/js/admin-comments.js** - Admin comment management functions

#### Database

- **database/migrate_add_comments_table.sql** - Comments table migration

### 3. Features

#### For Users

- View approved comments on any news article
- Post comments (require login)
- Character limit: 3-1000 characters
- Report inappropriate comments
- See other users' avatars and usernames

#### For Admin

- View all comments (pending, approved, reported)
- Approve pending comments
- Delete comments
- View reports with reasons
- Track report count

### 4. Moderation Workflow

1. **User submits comment** → Status: Pending (not visible)
2. **Admin reviews** → Can approve or delete
3. **Approved** → Visible to all users
4. **Reported** → Flagged for review
5. **Admin action** → Delete or ignore

### 5. Security Features

- Only authenticated users can comment
- Server-side input validation (3-1000 chars)
- SQL injection prevention (prepared statements)
- XSS protection (HTML escaping)
- CSRF protection via session

### 6. Integration with Admin Menu

Add this to your admin sidebar:

```html
<li class="nav-item">
  <a class="nav-link" href="<?= BASE_URL ?>admin/comment">
    <i class="fas fa-comments me-2"></i>
    <span>Bình luận</span>
  </a>
</li>
```

### 7. Usage in News Detail View

Comments section automatically appears on all news detail pages with:

- Login prompt for non-authenticated users
- Comment form for authenticated users
- Real-time comment count
- Report functionality

### 8. Admin Dashboard Access

URL: `/admin/comment`

Tabs:

- **All Comments** - Browse all comments
- **Pending** - Comments awaiting approval
- **Reported** - Comments flagged by users

### 9. Styling

All styling follows the project's design system:

- Colors: CGV Red (#E71A0F), Gray palette
- Spacing: 22px, 18px, 14px
- Border-radius: 12px
- Shadows: Consistent with project theme

### 10. Notes

- Comments require admin approval before visibility
- Default to pending (is_approved = 0)
- Character counter shows in real-time
- Report reasons stored for admin review
- Responsive design for all devices

## Troubleshooting

**Comments not loading:**

- Check if comments table exists in database
- Verify news_id is valid integer

**Approve/Delete not working:**

- Ensure admin check is implemented correctly
- Check AdminCommentController auth logic

**Styling issues:**

- Import admin-comments.js in admin layout
- Verify Bootstrap 5.3.3 is loaded

## Future Enhancements

- Nested replies/threading
- Comment likes/reactions
- Email notifications
- Auto-moderation (profanity filter)
- Comment pagination
- User ban system
