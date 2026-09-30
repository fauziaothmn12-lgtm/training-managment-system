CREATE DATABASE IF NOT EXISTS tms_db;
USE tms_db;

-- 1. Table: users
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Department Manager', 'Staff') DEFAULT 'Staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Table: departments
CREATE TABLE IF NOT EXISTS departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    manager_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manager_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 3. Table: staff
CREATE TABLE IF NOT EXISTS staff (
    staff_id INT AUTO_INCREMENT PRIMARY KEY,
    department_id INT NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    address TEXT,
    email VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    qualification VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(department_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Table: trainings
CREATE TABLE IF NOT EXISTS trainings (
    training_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    age_requirement INT NULL,
    qualification VARCHAR(200) NULL,
    country VARCHAR(50) NULL,
    region VARCHAR(50) NULL,
    place VARCHAR(100) NULL,
    profession VARCHAR(100) NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. Table: staff_training
CREATE TABLE IF NOT EXISTS staff_training (
    staff_id INT NOT NULL,
    training_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    attendance_status ENUM('approved', 'not_approved', 'pending') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (staff_id, training_id),
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE CASCADE,
    FOREIGN KEY (training_id) REFERENCES trainings(training_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Kuweka Admin Wa Mfano (Password: password123)
INSERT INTO users (name, username, email, phone_number, password, role) 
VALUES ('System Admin', 'admin', 'admin@mofp.go.tz', '0770000000', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1q.zH9Sg6xP5rMv9Q2B3vJ/YgRre4eK', 'Admin')
ON DUPLICATE KEY UPDATE user_id=user_id;