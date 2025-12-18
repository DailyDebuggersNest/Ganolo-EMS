-- 1. RESET DATABASE (Cleans up everything to start fresh)
DROP DATABASE IF EXISTS emsdb_23;
CREATE DATABASE emsdb_23;
USE emsdb_23;

-- 2. CREATE COURSE TABLE
CREATE TABLE `course` (
  `courseID` varchar(10) NOT NULL,       -- e.g. 'IT', 'HM'
  `course_code` varchar(20) NOT NULL,    -- e.g. 'BSIT', 'BSCS'
  `description` varchar(100) NOT NULL,   -- e.g. 'Bachelor of Science in...'
  PRIMARY KEY (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED COURSES
INSERT INTO `course` (`courseID`, `course_code`, `description`) VALUES
('IT', 'BSIT', 'Bachelor of Science in Information Technology'),
('CS', 'BSCS', 'Bachelor of Science in Computer Science'),
('HM', 'BSHM', 'Bachelor of Science in Hospitality Management'),
('NUR', 'BSN', 'Bachelor of Science in Nursing');

-- 3. CREATE CURRICULUM TABLE (With CurriculumID as Primary Key)
CREATE TABLE `curriculum` (
  `CurriculumID` int(11) NOT NULL AUTO_INCREMENT,
  `courseID` varchar(10) DEFAULT NULL,   -- Link to Course
  `subject_code` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `year_level` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `units` int(11) NOT NULL DEFAULT 3,
  PRIMARY KEY (`CurriculumID`),
  FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED CURRICULUM
INSERT INTO `curriculum` (`courseID`, `subject_code`, `description`, `year_level`, `semester`, `units`) VALUES
('IT', 'IT101', 'Introduction to Computing', 1, '1st', 3),
('IT', 'IT102', 'Computer Programming 1', 1, '1st', 3),
('CS', 'CS101', 'Intro to Algorithms', 1, '1st', 3),
('IT', 'IT201', 'Web Systems and Technologies', 2, '2nd', 3);

-- 4. CREATE STUDENTS TABLE
CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED STUDENTS
INSERT INTO `students` (`lastname`, `firstname`, `middlename`, `age`, `email`, `phone`) VALUES
('Garcia23', 'Sophia', 'Marie', 20, 'sophia@gmail.com', '09123456789'),
('Reyes23', 'Daniel', 'James', 21, 'daniel@gmail.com', '09123456790'),
('Santos23', 'Bianca', 'Rose', 19, 'bianca@gmail.com', '09123456791'),
('Cruz23', 'Patrick', 'Lee', 22, 'patrick@gmail.com', '09123456792'),
('Mendoza23', 'Clarisse', 'Ann', 20, 'clarisse@gmail.com', '09123456793'),
('Flores23', 'Anthony', 'John', 23, 'anthony@gmail.com', '09123456794'),
('Gonzales23', 'Mark', 'Rey', 21, 'mark@gmail.com', '09123456795'),
('Bautista23', 'Angel', 'Mae', 19, 'angel@gmail.com', '09123456796'),
('Villanueva23', 'Joshua', 'Paul', 20, 'joshua@gmail.com', '09123456797'),
('Delos23', 'Maria', 'Clara', 22, 'maria@gmail.com', '09123456798'),
('Ramos23', 'John', 'David', 21, 'john@gmail.com', '09123456799'),
('Lopez23', 'Sarah', 'Jean', 20, 'sarah@gmail.com', '09123456800'),
('Hernandez23', 'Miguel', 'Luis', 23, 'miguel@gmail.com', '09123456801'),
('Tan23', 'Andrea', 'Nicole', 19, 'andrea@gmail.com', '09123456802'),
('Lim23', 'Kevin', 'Charles', 20, 'kevin@gmail.com', '09123456803'),
('Torres23', 'Paula', 'Grace', 21, 'paula@gmail.com', '09123456804'),
('Diaz23', 'Ryan', 'Matthew', 22, 'ryan@gmail.com', '09123456805'),
('Castro23', 'Erica', 'Joy', 20, 'erica@gmail.com', '09123456806'),
('Aquino23', 'Francis', 'Jay', 21, 'francis@gmail.com', '09123456807'),
('Navarro23', 'Bea', 'Patricia', 19, 'bea@gmail.com', '09123456808'),
('Ezra23', 'Scarlet', 'Mil', 20, 'ezra@gmail.com', '09256233523');

-- 5. CREATE ENROLLMENTS TABLE
CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subject_id`) REFERENCES `curriculum` (`CurriculumID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- OPTIONAL: Seed Enrollments
INSERT INTO `enrollments` (`student_id`, `subject_id`) VALUES 
(1, 1), (1, 2), (2, 1);