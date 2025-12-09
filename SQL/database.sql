-- Create the database
CREATE DATABASE IF NOT EXISTS testems_database;
USE testems_database;

-- Students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,  
    lastname VARCHAR(50) NOT NULL,  
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    date_of_birth DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    credits INT DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Enrollments table (many-to-many)
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE(student_id, course_id)  -- Prevent duplicate enrollments
);

-- Sample data
INSERT INTO students (firstname, lastname, email, phone, date_of_birth) VALUES
('Isaac', 'Ganolo', 'isaac@gmail.com', '09123451234', '1995-05-15'),
('Jefferson', 'Cano', 'jeff@gmail.com', '09123551235', '1994-08-22'),
('Reginald', 'Cano', 'regi@gmail.com', '09123651236', '1996-12-05'),
('Maria', 'Lopez', 'maria@gmail.com', '09123751237', '1993-03-30'),
('Anna', 'Smith', 'anna@gmail.com', '09123851238', '1992-11-11');

INSERT INTO courses (title, description, credits) VALUES
('Introduction to PHP', 'Learn the basics of PHP programming.', 3),
('Web Development', 'Build responsive websites.', 4),
('Database Systems', 'Understand database design and SQL.', 3),
('Data Structures', 'Learn about various data structures.', 3),
('Algorithms', 'Study algorithms and problem-solving techniques.', 4);

INSERT INTO enrollments (student_id, course_id) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);