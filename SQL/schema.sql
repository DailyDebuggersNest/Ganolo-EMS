-- 1. RESET DATABASE
DROP DATABASE IF EXISTS emsdb_23;
CREATE DATABASE emsdb_23;
USE emsdb_23;

-- 2. CREATE COURSE TABLE
CREATE TABLE `course` (
  `courseID` varchar(10) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `course` (`courseID`, `course_code`, `description`) VALUES
('0001', 'BSIT', 'Bachelor of Science in Information Technology'),
('0002', 'BSCS', 'Bachelor of Science in Computer Science'),
('0003', 'BSHM', 'Bachelor of Science in Hospitality Management'),
('0004', 'BSCRIM', 'Bachelor of Science in Criminology'),
('0005', 'BSED', 'Bachelor of Science in Education');

-- 3. CREATE CURRICULUM TABLE
CREATE TABLE `curriculum` (
  `CurriculumID` int(11) NOT NULL AUTO_INCREMENT,
  `courseID` varchar(10) DEFAULT NULL,
  `subject_code` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `year_level` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `units` int(11) NOT NULL DEFAULT 3,
  PRIMARY KEY (`CurriculumID`),
  FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED COMPLETE 4-YEAR CURRICULUM (BSIT)
INSERT INTO `curriculum` (`courseID`, `subject_code`, `description`, `year_level`, `semester`, `units`) VALUES
-- Year 1, 1st Semester
('0001', 'IT 101', 'Introduction to Computing', 1, '1st', 3),
('0001', 'IT 102', 'Computer Programming 1', 1, '1st', 3),
('0001', 'MATH 101', 'College Algebra', 1, '1st', 3),
('0001', 'ENG 101', 'Purposive Communication', 1, '1st', 3),
('0001', 'PE 101', 'Physical Fitness', 1, '1st', 2),
('0001', 'NSTP 101', 'National Service Training Program 1', 1, '1st', 3),

-- Year 1, 2nd Semester
('0001', 'IT 103', 'Computer Programming 2', 1, '2nd', 3),
('0001', 'IT 104', 'Data Structures and Algorithms', 1, '2nd', 3),
('0001', 'MATH 102', 'Trigonometry', 1, '2nd', 3),
('0001', 'ENG 102', 'Contemporary World', 1, '2nd', 3),
('0001', 'PE 102', 'Rhythmic Activities', 1, '2nd', 2),
('0001', 'NSTP 102', 'National Service Training Program 2', 1, '2nd', 3),

-- Year 2, 1st Semester
('0001', 'IT 201', 'Object Oriented Programming', 2, '1st', 3),
('0001', 'IT 202', 'Digital Logic Design', 2, '1st', 3),
('0001', 'IT 203', 'Platform Technologies', 2, '1st', 3),
('0001', 'MATH 201', 'Discrete Mathematics', 2, '1st', 3),
('0001', 'PE 201', 'Individual and Dual Sports', 2, '1st', 2),
('0001', 'FIL 101', 'Komunikasyon sa Akademikong Filipino', 2, '1st', 3),

-- Year 2, 2nd Semester
('0001', 'IT 204', 'Information Management', 2, '2nd', 3),
('0001', 'IT 205', 'Networking 1', 2, '2nd', 3),
('0001', 'IT 206', 'Web Systems and Technologies', 2, '2nd', 3),
('0001', 'STAT 201', 'Probability and Statistics', 2, '2nd', 3),
('0001', 'PE 202', 'Team Sports', 2, '2nd', 2),
('0001', 'FIL 102', 'Pagbasa at Pagsulat', 2, '2nd', 3),

-- Year 3, 1st Semester
('0001', 'IT 301', 'Advanced Database Systems', 3, '1st', 3),
('0001', 'IT 302', 'Networking 2', 3, '1st', 3),
('0001', 'IT 303', 'System Integration and Architecture', 3, '1st', 3),
('0001', 'IT 304', 'Human Computer Interaction', 3, '1st', 3),
('0001', 'RES 301', 'Methods of Research', 3, '1st', 3),
('0001', 'ELEC 301', 'Professional Elective 1', 3, '1st', 3),

-- Year 3, 2nd Semester
('0001', 'IT 305', 'Systems Administration and Maintenance', 3, '2nd', 3),
('0001', 'IT 306', 'Information Assurance and Security', 3, '2nd', 3),
('0001', 'IT 307', 'Social and Professional Issues', 3, '2nd', 3),
('0001', 'IT 308', 'Software Engineering', 3, '2nd', 3),
('0001', 'ELEC 302', 'Professional Elective 2', 3, '2nd', 3),
('0001', 'RIZAL 101', 'Life and Works of Rizal', 3, '2nd', 3),

-- Year 4, 1st Semester
('0001', 'IT 401', 'Capstone Project 1', 4, '1st', 3),
('0001', 'IT 402', 'System Administration', 4, '1st', 3),
('0001', 'ELEC 401', 'Professional Elective 3', 4, '1st', 3),
('0001', 'ELEC 402', 'Professional Elective 4', 4, '1st', 3),
('0001', 'TECH 401', 'Technopreneurship', 4, '1st', 3),

-- Year 4, 2nd Semester
('0001', 'IT 403', 'Capstone Project 2', 4, '2nd', 3),
('0001', 'IT 404', 'Practicum / Internship (486 Hours)', 4, '2nd', 6),
('0001', 'SEM 401', 'Seminars and Field Trips', 4, '2nd', 3);


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

-- SEED 20 STUDENTS (Surnames ending in 23)
INSERT INTO `students` (`lastname`, `firstname`, `middlename`, `age`, `email`, `phone`) VALUES
('Garcia23', 'Sophia', 'Marie', 22, 'sophia@gmail.com', '09123456789'),
('Reyes23', 'Daniel', 'James', 21, 'daniel@gmail.com', '09123456790'),
('Santos23', 'Bianca', 'Rose', 23, 'bianca@gmail.com', '09123456791'),
('Cruz23', 'Patrick', 'Lee', 22, 'patrick@gmail.com', '09123456792'),
('Mendoza23', 'Clarisse', 'Ann', 21, 'clarisse@gmail.com', '09123456793'),
-- Other students (Freshmen/Sophomores/Juniors mixed)
('Flores23', 'Anthony', 'John', 20, 'anthony@gmail.com', '09123456794'),
('Gonzales23', 'Mark', 'Rey', 19, 'mark@gmail.com', '09123456795'),
('Bautista23', 'Angel', 'Mae', 19, 'angel@gmail.com', '09123456796'),
('Villanueva23', 'Joshua', 'Paul', 20, 'joshua@gmail.com', '09123456797'),
('Delos23', 'Maria', 'Clara', 18, 'maria@gmail.com', '09123456798'),
('Ramos23', 'John', 'David', 19, 'john@gmail.com', '09123456799'),
('Lopez23', 'Sarah', 'Jean', 20, 'sarah@gmail.com', '09123456800'),
('Hernandez23', 'Miguel', 'Luis', 21, 'miguel@gmail.com', '09123456801'),
('Tan23', 'Andrea', 'Nicole', 19, 'andrea@gmail.com', '09123456802'),
('Lim23', 'Kevin', 'Charles', 20, 'kevin@gmail.com', '09123456803'),
('Torres23', 'Paula', 'Grace', 21, 'paula@gmail.com', '09123456804'),
('Diaz23', 'Ryan', 'Matthew', 22, 'ryan@gmail.com', '09123456805'),
('Castro23', 'Erica', 'Joy', 20, 'erica@gmail.com', '09123456806'),
('Aquino23', 'Francis', 'Jay', 21, 'francis@gmail.com', '09123456807'),
('Navarro23', 'Bea', 'Patricia', 19, 'bea@gmail.com', '09123456808');

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

-- 6. CREATE GRADES TABLE
CREATE TABLE `grades` (
  `gradeID` int(11) NOT NULL AUTO_INCREMENT,
  `enrollment_id` int(11) NOT NULL,
  `midterm` varchar(5) DEFAULT NULL,
  `final` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`gradeID`),
  FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. CREATE SCHEDULE TABLE
CREATE TABLE `schedule` (
  `schedID` int(11) NOT NULL AUTO_INCREMENT,
  `enrollment_id` int(11) NOT NULL,
  `day` varchar(10) DEFAULT 'TBA',
  `time_in` varchar(10) DEFAULT NULL,
  `time_out` varchar(10) DEFAULT NULL,
  `room` varchar(20) DEFAULT 'TBA',
  PRIMARY KEY (`schedID`),
  FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. CREATE PAYMENTS TABLE
CREATE TABLE `payments` (
  `paymentID` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `year_level` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `school_year` varchar(20) NOT NULL,
  `payment_date` date NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `cost` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(10, 2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`paymentID`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- SEED DATA FOR STUDENT 1: SOPHIA GARCIA23
-- ==========================================================

-- --- YEAR 1, 1ST SEM (2022-2023) ---
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) 
SELECT 1, CurriculumID, '2022-08-15' FROM curriculum WHERE year_level=1 AND semester='1st';
-- Grades
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`)
SELECT id, '1.5', '1.25' FROM enrollments WHERE student_id=1 AND enrolled_at='2022-08-15';
-- Schedule (NEW: Added for Past Semesters)
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`)
SELECT id, 'MON/WED', '07:30 AM', '10:30 AM', 'ROOM 301' FROM enrollments WHERE student_id=1 AND enrolled_at='2022-08-15';
-- Payments (Realistic Breakdown)
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES
(1, 1, '1st', '2022-2023', '2022-08-10', 'Enrollment Downpayment', 5000, 5000),
(1, 1, '1st', '2022-2023', '2022-09-05', 'Miscellaneous Fees (Library, ID, Medical)', 3500, 3500),
(1, 1, '1st', '2022-2023', '2022-10-15', 'Prelim Tuition Installment', 4000, 4000),
(1, 1, '1st', '2022-2023', '2022-12-01', 'Final Tuition Balance', 4000, 4000);


-- --- YEAR 1, 2ND SEM (2022-2023) ---
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) 
SELECT 1, CurriculumID, '2023-01-15' FROM curriculum WHERE year_level=1 AND semester='2nd';
-- Grades
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`)
SELECT id, '1.75', '1.5' FROM enrollments WHERE student_id=1 AND enrolled_at='2023-01-15';
-- Schedule
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`)
SELECT id, 'TUE/THU', '01:00 PM', '04:00 PM', 'LAB 202' FROM enrollments WHERE student_id=1 AND enrolled_at='2023-01-15';
-- Payments
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES
(1, 1, '2nd', '2022-2023', '2023-01-15', 'Registration & Misc Fees', 5500, 5500),
(1, 1, '2nd', '2022-2023', '2023-03-15', 'Computer Laboratory Fee', 2500, 2500),
(1, 1, '2nd', '2022-2023', '2023-05-15', 'Tuition Full Payment', 8000, 8000);


-- --- YEAR 2, 1ST SEM (2023-2024) ---
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) 
SELECT 1, CurriculumID, '2023-08-15' FROM curriculum WHERE year_level=2 AND semester='1st';
-- Grades
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`)
SELECT id, '2.0', '1.75' FROM enrollments WHERE student_id=1 AND enrolled_at='2023-08-15';
-- Schedule
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`)
SELECT id, 'WED/FRI', '09:00 AM', '12:00 PM', 'ROOM 405' FROM enrollments WHERE student_id=1 AND enrolled_at='2023-08-15';
-- Payments
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES
(1, 2, '1st', '2023-2024', '2023-08-15', 'Downpayment', 5000, 5000),
(1, 2, '1st', '2023-2024', '2023-09-15', 'Student Organization Fee', 500, 500),
(1, 2, '1st', '2023-2024', '2023-11-15', 'Tuition Installment', 6000, 6000),
(1, 2, '1st', '2023-2024', '2023-12-15', 'Final Balance', 5000, 5000);


-- --- YEAR 2, 2ND SEM (2023-2024) ---
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) 
SELECT 1, CurriculumID, '2024-01-15' FROM curriculum WHERE year_level=2 AND semester='2nd';
-- Grades
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`)
SELECT id, '1.25', '1.0' FROM enrollments WHERE student_id=1 AND enrolled_at='2024-01-15';
-- Schedule
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`)
SELECT id, 'MON/THU', '02:00 PM', '05:00 PM', 'LAB 101' FROM enrollments WHERE student_id=1 AND enrolled_at='2024-01-15';
-- Payments
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES
(1, 2, '2nd', '2023-2024', '2024-01-15', 'Misc & Registration Fees', 6000, 6000),
(1, 2, '2nd', '2023-2024', '2024-03-01', 'Network Lab Fee', 3000, 3000),
(1, 2, '2nd', '2023-2024', '2024-05-15', 'Tuition Fee Balance', 9000, 9000);


-- --- YEAR 3, 1ST SEM (2024-2025) ---
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) 
SELECT 1, CurriculumID, '2024-08-15' FROM curriculum WHERE year_level=3 AND semester='1st';
-- Grades
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`)
SELECT id, '1.5', '1.5' FROM enrollments WHERE student_id=1 AND enrolled_at='2024-08-15';
-- Schedule
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`)
SELECT id, 'TUE/FRI', '08:00 AM', '11:00 AM', 'AVR 1' FROM enrollments WHERE student_id=1 AND enrolled_at='2024-08-15';
-- Payments
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES
(1, 3, '1st', '2024-2025', '2024-08-15', 'Enrollment Downpayment', 5000, 5000),
(1, 3, '1st', '2024-2025', '2024-09-20', 'Research Defense Fee (Initial)', 1500, 1500),
(1, 3, '1st', '2024-2025', '2024-11-15', 'Tuition Midterm', 6000, 6000),
(1, 3, '1st', '2024-2025', '2024-12-20', 'Tuition Final', 6000, 6000);


-- --- YEAR 3, 2ND SEM (2024-2025) ---
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) 
SELECT 1, CurriculumID, '2025-01-15' FROM curriculum WHERE year_level=3 AND semester='2nd';
-- Grades
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`)
SELECT id, '1.75', '1.75' FROM enrollments WHERE student_id=1 AND enrolled_at='2025-01-15';
-- Schedule
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`)
SELECT id, 'MON/WED', '01:00 PM', '04:00 PM', 'COMP LAB 4' FROM enrollments WHERE student_id=1 AND enrolled_at='2025-01-15';
-- Payments
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES
(1, 3, '2nd', '2024-2025', '2025-01-15', 'Full Payment (Discounted)', 17500, 17500);


-- --- YEAR 4, 1ST SEM (CURRENT - 2025-2026) ---
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) 
SELECT 1, CurriculumID, '2025-08-15' FROM curriculum WHERE year_level=4 AND semester='1st';
-- Schedule (Current)
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`)
SELECT id, 'FRI', '08:00 AM', '05:00 PM', 'CAPSTONE LAB' FROM enrollments WHERE student_id=1 AND enrolled_at='2025-08-15';
-- Payments (Partial/Pending)
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES
(1, 4, '1st', '2025-2026', '2025-08-15', 'Enrollment Downpayment', 5000, 5000),
(1, 4, '1st', '2025-2026', '2025-09-01', 'Capstone Adviser Fee', 2500, 2500),
(1, 4, '1st', '2025-2026', '2025-09-01', 'Graduation Fee (Partial)', 2000, 0), -- Unpaid
(1, 4, '1st', '2025-2026', '2025-10-15', 'Midterm Tuition', 6000, 0); -- Unpaid


-- ==========================================================
-- QUICK FILL FOR OTHER STUDENTS (2-5)
-- ==========================================================

-- Student 2 (Daniel Reyes23)
-- Y1S1
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) SELECT 2, CurriculumID, '2022-08-15' FROM curriculum WHERE year_level=1 AND semester='1st';
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`) SELECT id, '2.0', '2.0' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2022%';
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`) SELECT id, 'TUE/THU', '08:00 AM', '11:00 AM', 'ROOM 101' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2022%';
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES (2, 1, '1st', '2022-2023', '2022-08-15', 'Full Payment', 16000, 16000);

-- Y1S2
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) SELECT 2, CurriculumID, '2023-01-15' FROM curriculum WHERE year_level=1 AND semester='2nd';
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`) SELECT id, '2.25', '2.0' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2023-01%';
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`) SELECT id, 'MON/WED', '01:00 PM', '04:00 PM', 'ROOM 102' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2023-01%';
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES (2, 1, '2nd', '2022-2023', '2023-01-15', 'Full Payment', 16000, 16000);

-- Y2S1
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) SELECT 2, CurriculumID, '2023-08-15' FROM curriculum WHERE year_level=2 AND semester='1st';
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`) SELECT id, '1.75', '1.75' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2023-08%';
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`) SELECT id, 'FRI', '09:00 AM', '12:00 PM', 'LAB 3' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2023-08%';
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES (2, 2, '1st', '2023-2024', '2023-08-15', 'Full Payment', 17000, 17000);

-- Y2S2
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) SELECT 2, CurriculumID, '2024-01-15' FROM curriculum WHERE year_level=2 AND semester='2nd';
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`) SELECT id, '1.5', '1.5' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2024-01%';
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`) SELECT id, 'WED', '02:00 PM', '05:00 PM', 'LAB 4' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2024-01%';
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES (2, 2, '2nd', '2023-2024', '2024-01-15', 'Full Payment', 17000, 17000);

-- Y3S1
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) SELECT 2, CurriculumID, '2024-08-15' FROM curriculum WHERE year_level=3 AND semester='1st';
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`) SELECT id, '2.5', '2.0' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2024-08%';
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`) SELECT id, 'MON', '08:00 AM', '11:00 AM', 'LEC 1' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2024-08%';
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES (2, 3, '1st', '2024-2025', '2024-08-15', 'Full Payment', 18000, 18000);

-- Y3S2
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) SELECT 2, CurriculumID, '2025-01-15' FROM curriculum WHERE year_level=3 AND semester='2nd';
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`) SELECT id, '2.0', '2.25' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2025-01%';
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`) SELECT id, 'THU', '01:00 PM', '04:00 PM', 'LEC 2' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2025-01%';
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES (2, 3, '2nd', '2024-2025', '2025-01-15', 'Full Payment', 18000, 18000);

-- Y4S1 (Current)
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) SELECT 2, CurriculumID, '2025-08-15' FROM curriculum WHERE year_level=4 AND semester='1st';
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`) SELECT id, 'TUE/THU', '09:00 AM', '12:00 PM', 'LAB 2' FROM enrollments WHERE student_id=2 AND enrolled_at LIKE '2025-08%';
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES 
(2, 4, '1st', '2025-2026', '2025-08-15', 'Downpayment', 5000, 5000),
(2, 4, '1st', '2025-2026', '2025-09-10', 'Miscellaneous Fees', 4000, 2000), -- Partial
(2, 4, '1st', '2025-2026', '2025-10-10', 'Tuition', 8000, 0); -- Unpaid

-- Fill 3,4,5 with Enrollments & Schedule for all years (Simplified)
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) SELECT 3, CurriculumID, '2022-08-15' FROM curriculum WHERE year_level IN (1,2,3,4);
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) SELECT 4, CurriculumID, '2022-08-15' FROM curriculum WHERE year_level IN (1,2,3,4);
INSERT INTO `enrollments` (`student_id`, `subject_id`, `enrolled_at`) SELECT 5, CurriculumID, '2022-08-15' FROM curriculum WHERE year_level IN (1,2,3,4);

-- Grades & Schedule for 3,4,5
INSERT INTO `grades` (`enrollment_id`, `midterm`, `final`) SELECT id, '1.5', '1.5' FROM enrollments WHERE student_id IN (3,4,5);
INSERT INTO `schedule` (`enrollment_id`, `day`, `time_in`, `time_out`, `room`) SELECT id, 'SAT', '08:00 AM', '05:00 PM', 'VARIOUS' FROM enrollments WHERE student_id IN (3,4,5);

-- Payments for 3,4,5 (Current Year only for brevity)
INSERT INTO `payments` (`student_id`, `year_level`, `semester`, `school_year`, `payment_date`, `remarks`, `cost`, `amount_paid`) VALUES 
(3, 4, '1st', '2025-2026', '2025-08-15', 'Total Assessment', 20000, 20000),
(4, 4, '1st', '2025-2026', '2025-08-15', 'Total Assessment', 20000, 5000),
(5, 4, '1st', '2025-2026', '2025-08-15', 'Total Assessment', 20000, 10000);