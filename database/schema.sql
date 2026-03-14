-- Phase-1 baseline schema for team integration
-- MySQL 8+

CREATE DATABASE IF NOT EXISTS company_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE company_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('guest', 'member', 'admin') NOT NULL DEFAULT 'member',
    avatar VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'locked') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    price DECIMAL(12, 2) NOT NULL DEFAULT 0,
    description TEXT,
    image VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    slug VARCHAR(240) NOT NULL UNIQUE,
    summary TEXT,
    content LONGTEXT NOT NULL,
    cover_image VARCHAR(255) DEFAULT NULL,
    author_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_news_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(180) DEFAULT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, slug, price, description)
VALUES
    ('Goi Website Co Ban', 'goi-website-co-ban', 4900000, 'Landing page responsive.'),
    ('Goi Website Ban Hang', 'goi-website-ban-hang', 9900000, 'Danh sach san pham + gio hang.'),
    ('Goi Website Doanh Nghiep', 'goi-website-doanh-nghiep', 15900000, 'Tin tuc, SEO, dashboard.');
