-- Disable foreign key checks to avoid issues with table dependencies
SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS students;


-- Create Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);

-- Insert initial users
INSERT INTO users (username, password, role) VALUES
('admin', 'admin123', 'Admission-team'),
('ravi', 'hod@123', 'HoD'),
('kiran', 'prof@123', 'Professor'),
('krishna', 'abc123', 'Student'),
('shyam', 'shyam987', 'Student'),
('gopal', 'qwerty', 'Student');

-- Create Students Table
CREATE TABLE IF NOT EXISTS students (
    roll_number INT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    cgpa DECIMAL(3,2) DEFAULT NULL,
    attendance INT(2) DEFAULT NULL,
    fee_status VARCHAR(50) DEFAULT NULL,
    scholarship VARCHAR(50) DEFAULT 'NA'
);

-- Insert sample data into students
INSERT INTO students (roll_number, username, cgpa, attendance, fee_status, scholarship)
VALUES 
    (1001, 'krishna', 8.4, 92, 'Paid', 0),
    (1002, 'shyam', 7.5, 87, 'Unpaid', 0),
    (1003, 'gopal', 6.8, 76, 'Paid', 0);

-- Enable foreign key checks again
SET FOREIGN_KEY_CHECKS = 1;
