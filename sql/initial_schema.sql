-- Database: health_student_info_system

CREATE DATABASE IF NOT EXISTS health_student_info_system;
USE health_student_info_system;

-- Principals table
CREATE TABLE IF NOT EXISTS principals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    elementary_school VARCHAR(100) NOT NULL,
    contact_info VARCHAR(100) NOT NULL,
    nurse_email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Teachers table
CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    grade_level ENUM('Kinder', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6') NOT NULL,
    address VARCHAR(255) NOT NULL,
    contact_info VARCHAR(100) NOT NULL,
    principal_email VARCHAR(100) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Nurses table
CREATE TABLE IF NOT EXISTS nurses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    birthdate DATE NULL,
    address VARCHAR(255) NULL,
    contact_info VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Schools table
CREATE TABLE IF NOT EXISTS schools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    lrn VARCHAR(20) NOT NULL UNIQUE,
    principal_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Classes/Grade Levels table
CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT NOT NULL,
    grade_level ENUM('Kinder', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6') NOT NULL,
    teacher_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    birthdate DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Health Records table
CREATE TABLE IF NOT EXISTS health_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    height_cm DECIMAL(5,2),
    weight_kg DECIMAL(5,2),
    bmi DECIMAL(5,2),
    record_date DATE NOT NULL,
    nurse_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Nurse Requests table
CREATE TABLE IF NOT EXISTS nurse_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT NOT NULL,
    nurse_email VARCHAR(100) NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    responded_at TIMESTAMP NULL
);

-- Nurse-Schools (many-to-many) table
CREATE TABLE IF NOT EXISTS nurse_schools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nurse_id INT NOT NULL,
    school_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); 
