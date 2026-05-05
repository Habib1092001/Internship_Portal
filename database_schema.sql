-- =====================================================
-- INTERNSHIP PORTAL DATABASE SCHEMA
-- =====================================================
-- Database: internship_portal
-- Created: January 2026

-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS internship_portal;
USE internship_portal;

-- Drop existing tables if they exist (in reverse dependency order)
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS internships;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS users;

-- =====================================================
-- TABLE: users
-- Description: Stores user account information (Students, Companies, Admins)
-- =====================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    cgpa DECIMAL(3, 2),
    role ENUM('user', 'company', 'admin') NOT NULL DEFAULT 'user',
    password VARCHAR(255) NOT NULL,
    profile_photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_created_at (created_at)
);

-- =====================================================
-- TABLE: admins
-- Description: Admin credentials (separate table for admin access)
-- =====================================================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);

-- =====================================================
-- TABLE: companies
-- Description: Company profile information linked to company users
-- =====================================================
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    company_name VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    phone VARCHAR(20),
    website VARCHAR(255),
    description TEXT,
    logo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_company_name (company_name),
    INDEX idx_created_at (created_at)
);

-- =====================================================
-- TABLE: internships
-- Description: Internship job postings by companies
-- =====================================================
CREATE TABLE internships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    duration VARCHAR(100),
    salary VARCHAR(100),
    skills VARCHAR(255),
    stack VARCHAR(255),
    description TEXT,
    deadline DATE,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    INDEX idx_company_id (company_id),
    INDEX idx_status (status),
    INDEX idx_deadline (deadline),
    INDEX idx_created_at (created_at)
);

-- =====================================================
-- TABLE: applications
-- Description: Application records for internship positions
-- =====================================================
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    internship_id INT NOT NULL,
    user_id INT NOT NULL,
    cv VARCHAR(255),
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_application (internship_id, user_id),
    FOREIGN KEY (internship_id) REFERENCES internships(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_internship_id (internship_id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- =====================================================
-- TABLE: notifications
-- Description: User notifications for application status updates
-- =====================================================
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
);

-- =====================================================
-- Sample Data (Optional - for testing)
-- =====================================================

-- Insert sample admin
INSERT INTO admins (email, password) VALUES 
('admin@internshipportal.com', 'admin123');

-- Insert sample user (student)
INSERT INTO users (name, email, phone, address, cgpa, role, password, profile_photo) VALUES 
('John Doe', 'student@example.com', '1234567890', '123 Main St', 3.85, 'user', '$2y$10$nOQm8.xVKYrYQIIhvMuJyeYHmL.5qLvFvZkKLZ1vC1VZpLbKK8Omu', NULL);

-- Insert sample company user
INSERT INTO users (name, email, phone, address, role, password) VALUES 
('Tech Innovations Inc', 'company@techinnovations.com', '9876543210', '456 Tech Ave', 'company', '$2y$10$nOQm8.xVKYrYQIIhvMuJyeYHmL.5qLvFvZkKLZ1vC1VZpLbKK8Omu');

-- Insert sample company profile
INSERT INTO companies (user_id, company_name, address, phone, website, description) VALUES 
(2, 'Tech Innovations Inc', '456 Tech Ave', '9876543210', 'https://techinnovations.com', 'Leading software development company');

-- Insert sample internship
INSERT INTO internships (company_id, title, location, duration, salary, skills, stack, description, deadline, status) VALUES 
(1, 'Web Development Intern', 'New York, NY', '3 months', '$15/hour', 'React, Node.js, MongoDB', 'MERN Stack', 'Join our team to work on cutting-edge web applications', '2026-02-28', 'approved');

-- Insert sample application
INSERT INTO applications (internship_id, user_id, cv, status) VALUES 
(1, 1, '1735903200_65a1b2c3d4e5f.pdf', 'pending');

-- Insert sample notification
INSERT INTO notifications (user_id, message, is_read) VALUES 
(1, 'Your application has been submitted successfully.', 0);

-- =====================================================
-- End of Database Schema
-- =====================================================
