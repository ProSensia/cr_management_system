-- Database schema for CR Management System
CREATE DATABASE IF NOT EXISTS crms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crms;

-- Students table
CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150),
  phone VARCHAR(50),
  roll_no VARCHAR(50),
  batch VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Assignments / Quizzes table
CREATE TABLE IF NOT EXISTS tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  due_date DATE,
  due_time TIME,
  remind_before_minutes INT DEFAULT 1440, -- default remind 1 day before
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Task recipients (which students should receive reminder)
CREATE TABLE IF NOT EXISTS task_recipients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  task_id INT NOT NULL,
  student_id INT NOT NULL,
  sent BOOLEAN DEFAULT FALSE,
  last_sent_at TIMESTAMP NULL,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Example data
INSERT INTO students (name, email, phone, roll_no, batch) VALUES
('Alice Khan','alice@example.com','+923001112233','CS-01','2024'),
('Bilal Ahmed','bilal@example.com','+923009998877','CS-02','2024');

INSERT INTO tasks (title, description, due_date, due_time, remind_before_minutes) VALUES
('Assignment 1', 'Complete chapter 1 exercises', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '09:00:00', 1440),
('Quiz 1', 'MCQ quiz on week 1', DATE_ADD(CURDATE(), INTERVAL 2 DAY), '11:00:00', 2880);

INSERT INTO task_recipients (task_id, student_id) VALUES
(1,1),(1,2),(2,1),(2,2);
