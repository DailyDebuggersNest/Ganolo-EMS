-- RESET DATABASE

CREATE DATABASE IF NOT EXISTS emsdb_23;
USE emsdb_23;

-- 1. NEW STUDENTS TABLE (Reordered as requested)
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

-- SEED 20 STUDENTS (Lastname ends with 23)
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
('Navarro23', 'Bea', 'Patricia', 19, 'bea@gmail.com', '09123456808');

-- 2. NEW CURRICULUM TABLE 
CREATE TABLE `curriculum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_code` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `year_level` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `units` int(11) NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED CURRICULUM DATA
INSERT INTO `curriculum` (`subject_code`, `description`, `year_level`, `semester`, `units`) VALUES
('IT101', 'Introduction to Computing', 1, '1st', 3),
('IT102', 'Computer Programming 1', 1, '1st', 3),
('IT103', 'Data Structures', 2, '1st', 3),
('IT201', 'Web Systems and Technologies', 2, '2nd', 3),
('IT202', 'Database Management Systems', 2, '2nd', 3);

-- 3. ENROLLMENTS (Updated to link to curriculum)
CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subject_id`) REFERENCES `curriculum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;