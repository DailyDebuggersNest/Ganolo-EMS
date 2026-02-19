-- ============================================
-- ICE-EMS: FULL 5-COURSE CURRICULUM SEEDER
-- Includes: BSIT, BSCS, BSHM, BSCRIM, BSED
-- With realistic grades & varied schedules
-- ============================================

USE emsdb_23;

-- ============================================
-- STEP 1: CLEAR EXISTING DATA (Safe Reset)
-- ============================================
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE grades;
TRUNCATE TABLE schedule;
TRUNCATE TABLE payments;
TRUNCATE TABLE enrollments;
TRUNCATE TABLE curriculum;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- STEP 2: SEED CURRICULUM FOR ALL 5 COURSES
-- ============================================

-- ══════════════════════════════════════════
-- BSIT - Bachelor of Science in Information Technology (Course ID: 0001)
-- ══════════════════════════════════════════
INSERT INTO curriculum (courseID, subject_code, description, year_level, semester, units) VALUES
-- Year 1, 1st Sem
('0001', 'IT 101', 'Introduction to Computing', 1, '1st', 3),
('0001', 'IT 102', 'Computer Programming 1', 1, '1st', 3),
('0001', 'MATH 101', 'College Algebra', 1, '1st', 3),
('0001', 'ENG 101', 'Purposive Communication', 1, '1st', 3),
('0001', 'PE 101', 'Physical Fitness', 1, '1st', 2),
('0001', 'NSTP 101', 'National Service Training Program 1', 1, '1st', 3),
-- Year 1, 2nd Sem
('0001', 'IT 103', 'Computer Programming 2', 1, '2nd', 3),
('0001', 'IT 104', 'Data Structures and Algorithms', 1, '2nd', 3),
('0001', 'MATH 102', 'Plane Trigonometry', 1, '2nd', 3),
('0001', 'ENG 102', 'The Contemporary World', 1, '2nd', 3),
('0001', 'PE 102', 'Rhythmic Activities', 1, '2nd', 2),
('0001', 'NSTP 102', 'National Service Training Program 2', 1, '2nd', 3),
-- Year 2, 1st Sem
('0001', 'IT 201', 'Object Oriented Programming', 2, '1st', 3),
('0001', 'IT 202', 'Digital Logic Design', 2, '1st', 3),
('0001', 'IT 203', 'Platform Technologies', 2, '1st', 3),
('0001', 'MATH 201', 'Discrete Mathematics', 2, '1st', 3),
('0001', 'PE 201', 'Individual and Dual Sports', 2, '1st', 2),
('0001', 'FIL 101', 'Komunikasyon sa Akademikong Filipino', 2, '1st', 3),
-- Year 2, 2nd Sem
('0001', 'IT 204', 'Information Management', 2, '2nd', 3),
('0001', 'IT 205', 'Networking 1', 2, '2nd', 3),
('0001', 'IT 206', 'Web Systems and Technologies', 2, '2nd', 3),
('0001', 'STAT 201', 'Probability and Statistics', 2, '2nd', 3),
('0001', 'PE 202', 'Team Sports', 2, '2nd', 2),
('0001', 'FIL 102', 'Pagbasa at Pagsulat', 2, '2nd', 3),
-- Year 3, 1st Sem
('0001', 'IT 301', 'Advanced Database Systems', 3, '1st', 3),
('0001', 'IT 302', 'Networking 2', 3, '1st', 3),
('0001', 'IT 303', 'System Integration and Architecture', 3, '1st', 3),
('0001', 'IT 304', 'Human Computer Interaction', 3, '1st', 3),
('0001', 'RES 301', 'Methods of Research', 3, '1st', 3),
('0001', 'ELEC 301', 'Mobile Application Development', 3, '1st', 3),
-- Year 3, 2nd Sem
('0001', 'IT 305', 'Systems Administration and Maintenance', 3, '2nd', 3),
('0001', 'IT 306', 'Information Assurance and Security', 3, '2nd', 3),
('0001', 'IT 307', 'Social and Professional Issues in IT', 3, '2nd', 3),
('0001', 'IT 308', 'Software Engineering', 3, '2nd', 3),
('0001', 'ELEC 302', 'Cloud Computing', 3, '2nd', 3),
('0001', 'RIZAL 101', 'Life and Works of Rizal', 3, '2nd', 3),
-- Year 4, 1st Sem
('0001', 'IT 401', 'Capstone Project 1', 4, '1st', 3),
('0001', 'IT 402', 'System Administration', 4, '1st', 3),
('0001', 'ELEC 401', 'Internet of Things', 4, '1st', 3),
('0001', 'ELEC 402', 'Data Analytics', 4, '1st', 3),
('0001', 'TECH 401', 'Technopreneurship', 4, '1st', 3),
-- Year 4, 2nd Sem
('0001', 'IT 403', 'Capstone Project 2', 4, '2nd', 3),
('0001', 'IT 404', 'Practicum (486 Hours)', 4, '2nd', 6),
('0001', 'SEM 401', 'Seminars and Field Trips', 4, '2nd', 3);

-- ══════════════════════════════════════════
-- BSCS - Bachelor of Science in Computer Science (Course ID: 0002)
-- ══════════════════════════════════════════
INSERT INTO curriculum (courseID, subject_code, description, year_level, semester, units) VALUES
-- Year 1, 1st Sem
('0002', 'CS 101', 'Introduction to Computer Science', 1, '1st', 3),
('0002', 'CS 102', 'Fundamentals of Programming', 1, '1st', 3),
('0002', 'MATH 111', 'Calculus 1', 1, '1st', 3),
('0002', 'ENG 101', 'Purposive Communication', 1, '1st', 3),
('0002', 'PE 101', 'Physical Fitness', 1, '1st', 2),
('0002', 'NSTP 101', 'National Service Training Program 1', 1, '1st', 3),
-- Year 1, 2nd Sem
('0002', 'CS 103', 'Intermediate Programming', 1, '2nd', 3),
('0002', 'CS 104', 'Discrete Structures 1', 1, '2nd', 3),
('0002', 'MATH 112', 'Calculus 2', 1, '2nd', 3),
('0002', 'ENG 102', 'The Contemporary World', 1, '2nd', 3),
('0002', 'PE 102', 'Rhythmic Activities', 1, '2nd', 2),
('0002', 'NSTP 102', 'National Service Training Program 2', 1, '2nd', 3),
-- Year 2, 1st Sem
('0002', 'CS 201', 'Data Structures', 2, '1st', 3),
('0002', 'CS 202', 'Discrete Structures 2', 2, '1st', 3),
('0002', 'CS 203', 'Computer Organization', 2, '1st', 3),
('0002', 'MATH 211', 'Linear Algebra', 2, '1st', 3),
('0002', 'PE 201', 'Individual and Dual Sports', 2, '1st', 2),
('0002', 'FIL 101', 'Komunikasyon sa Akademikong Filipino', 2, '1st', 3),
-- Year 2, 2nd Sem
('0002', 'CS 204', 'Algorithms and Complexity', 2, '2nd', 3),
('0002', 'CS 205', 'Architecture and Organization', 2, '2nd', 3),
('0002', 'CS 206', 'Information Management', 2, '2nd', 3),
('0002', 'STAT 211', 'Probability and Statistics', 2, '2nd', 3),
('0002', 'PE 202', 'Team Sports', 2, '2nd', 2),
('0002', 'FIL 102', 'Pagbasa at Pagsulat', 2, '2nd', 3),
-- Year 3, 1st Sem
('0002', 'CS 301', 'Automata Theory and Formal Languages', 3, '1st', 3),
('0002', 'CS 302', 'Operating Systems', 3, '1st', 3),
('0002', 'CS 303', 'Programming Languages', 3, '1st', 3),
('0002', 'CS 304', 'Software Engineering 1', 3, '1st', 3),
('0002', 'RES 301', 'Methods of Research', 3, '1st', 3),
('0002', 'ELEC 301', 'Artificial Intelligence', 3, '1st', 3),
-- Year 3, 2nd Sem
('0002', 'CS 305', 'Networks and Communications', 3, '2nd', 3),
('0002', 'CS 306', 'Software Engineering 2', 3, '2nd', 3),
('0002', 'CS 307', 'Information Assurance and Security', 3, '2nd', 3),
('0002', 'CS 308', 'Parallel and Distributed Computing', 3, '2nd', 3),
('0002', 'ELEC 302', 'Machine Learning', 3, '2nd', 3),
('0002', 'RIZAL 101', 'Life and Works of Rizal', 3, '2nd', 3),
-- Year 4, 1st Sem
('0002', 'CS 401', 'Thesis Writing 1', 4, '1st', 3),
('0002', 'CS 402', 'Social Issues and Professional Practice', 4, '1st', 3),
('0002', 'ELEC 401', 'Computer Graphics', 4, '1st', 3),
('0002', 'ELEC 402', 'Natural Language Processing', 4, '1st', 3),
('0002', 'TECH 401', 'Technopreneurship', 4, '1st', 3),
-- Year 4, 2nd Sem
('0002', 'CS 403', 'Thesis Writing 2', 4, '2nd', 3),
('0002', 'CS 404', 'Practicum (486 Hours)', 4, '2nd', 6),
('0002', 'SEM 401', 'Seminars and Field Trips', 4, '2nd', 3);

-- ══════════════════════════════════════════
-- BSHM - Bachelor of Science in Hospitality Management (Course ID: 0003)
-- ══════════════════════════════════════════
INSERT INTO curriculum (courseID, subject_code, description, year_level, semester, units) VALUES
-- Year 1, 1st Sem
('0003', 'HM 101', 'Introduction to Hospitality Industry', 1, '1st', 3),
('0003', 'HM 102', 'Food Safety and Sanitation', 1, '1st', 3),
('0003', 'HM 103', 'Culinary Arts and Kitchen Management', 1, '1st', 3),
('0003', 'ENG 101', 'Purposive Communication', 1, '1st', 3),
('0003', 'PE 101', 'Physical Fitness', 1, '1st', 2),
('0003', 'NSTP 101', 'National Service Training Program 1', 1, '1st', 3),
-- Year 1, 2nd Sem
('0003', 'HM 104', 'Front Office Operations', 1, '2nd', 3),
('0003', 'HM 105', 'Housekeeping Operations', 1, '2nd', 3),
('0003', 'HM 106', 'Food and Beverage Service', 1, '2nd', 3),
('0003', 'ENG 102', 'The Contemporary World', 1, '2nd', 3),
('0003', 'PE 102', 'Rhythmic Activities', 1, '2nd', 2),
('0003', 'NSTP 102', 'National Service Training Program 2', 1, '2nd', 3),
-- Year 2, 1st Sem
('0003', 'HM 201', 'Bar and Beverage Management', 2, '1st', 3),
('0003', 'HM 202', 'Tourism Geography', 2, '1st', 3),
('0003', 'HM 203', 'Quantity Food Production', 2, '1st', 3),
('0003', 'MATH 101', 'Business Mathematics', 2, '1st', 3),
('0003', 'PE 201', 'Individual and Dual Sports', 2, '1st', 2),
('0003', 'FIL 101', 'Komunikasyon sa Akademikong Filipino', 2, '1st', 3),
-- Year 2, 2nd Sem
('0003', 'HM 204', 'Hotel and Restaurant Law', 2, '2nd', 3),
('0003', 'HM 205', 'Catering Management', 2, '2nd', 3),
('0003', 'HM 206', 'Cost Control in Hospitality', 2, '2nd', 3),
('0003', 'ACCT 201', 'Basic Accounting', 2, '2nd', 3),
('0003', 'PE 202', 'Team Sports', 2, '2nd', 2),
('0003', 'FIL 102', 'Pagbasa at Pagsulat', 2, '2nd', 3),
-- Year 3, 1st Sem
('0003', 'HM 301', 'Hotel and Resort Management', 3, '1st', 3),
('0003', 'HM 302', 'Event Management', 3, '1st', 3),
('0003', 'HM 303', 'Hospitality Marketing', 3, '1st', 3),
('0003', 'HM 304', 'Human Resource Management in Hospitality', 3, '1st', 3),
('0003', 'RES 301', 'Methods of Research', 3, '1st', 3),
('0003', 'ELEC 301', 'International Cuisine', 3, '1st', 3),
-- Year 3, 2nd Sem
('0003', 'HM 305', 'Strategic Management in Hospitality', 3, '2nd', 3),
('0003', 'HM 306', 'Hospitality Information Systems', 3, '2nd', 3),
('0003', 'HM 307', 'Quality Service Management', 3, '2nd', 3),
('0003', 'HM 308', 'Filipino Cuisine and Culture', 3, '2nd', 3),
('0003', 'ELEC 302', 'Wine Appreciation', 3, '2nd', 3),
('0003', 'RIZAL 101', 'Life and Works of Rizal', 3, '2nd', 3),
-- Year 4, 1st Sem
('0003', 'HM 401', 'Research in Hospitality 1', 4, '1st', 3),
('0003', 'HM 402', 'Facilities Planning and Design', 4, '1st', 3),
('0003', 'ELEC 401', 'Cruise Line Operations', 4, '1st', 3),
('0003', 'ELEC 402', 'Spa and Wellness Management', 4, '1st', 3),
('0003', 'TECH 401', 'Hospitality Entrepreneurship', 4, '1st', 3),
-- Year 4, 2nd Sem
('0003', 'HM 403', 'Research in Hospitality 2', 4, '2nd', 3),
('0003', 'HM 404', 'Practicum (600 Hours)', 4, '2nd', 6),
('0003', 'SEM 401', 'Industry Seminars', 4, '2nd', 3);

-- ══════════════════════════════════════════
-- BSCRIM - Bachelor of Science in Criminology (Course ID: 0004)
-- ══════════════════════════════════════════
INSERT INTO curriculum (courseID, subject_code, description, year_level, semester, units) VALUES
-- Year 1, 1st Sem
('0004', 'CRIM 101', 'Introduction to Criminology', 1, '1st', 3),
('0004', 'CRIM 102', 'Philippine Criminal Justice System', 1, '1st', 3),
('0004', 'CRIM 103', 'Fundamentals of Criminal Investigation', 1, '1st', 3),
('0004', 'ENG 101', 'Purposive Communication', 1, '1st', 3),
('0004', 'PE 101', 'Physical Fitness and Self-Defense', 1, '1st', 2),
('0004', 'NSTP 101', 'National Service Training Program 1', 1, '1st', 3),
-- Year 1, 2nd Sem
('0004', 'CRIM 104', 'Theories of Crime Causation', 1, '2nd', 3),
('0004', 'CRIM 105', 'Ethics and Values in Criminology', 1, '2nd', 3),
('0004', 'CRIM 106', 'Forensic Photography', 1, '2nd', 3),
('0004', 'ENG 102', 'The Contemporary World', 1, '2nd', 3),
('0004', 'PE 102', 'Martial Arts', 1, '2nd', 2),
('0004', 'NSTP 102', 'National Service Training Program 2', 1, '2nd', 3),
-- Year 2, 1st Sem
('0004', 'CRIM 201', 'Criminal Law 1 (Revised Penal Code)', 2, '1st', 3),
('0004', 'CRIM 202', 'Law Enforcement Organization', 2, '1st', 3),
('0004', 'CRIM 203', 'Personal Identification Techniques', 2, '1st', 3),
('0004', 'CRIM 204', 'Criminalistics 1', 2, '1st', 3),
('0004', 'PE 201', 'Combative Sports', 2, '1st', 2),
('0004', 'FIL 101', 'Komunikasyon sa Akademikong Filipino', 2, '1st', 3),
-- Year 2, 2nd Sem
('0004', 'CRIM 205', 'Criminal Law 2 (Special Laws)', 2, '2nd', 3),
('0004', 'CRIM 206', 'Police Operations and Management', 2, '2nd', 3),
('0004', 'CRIM 207', 'Criminalistics 2', 2, '2nd', 3),
('0004', 'CRIM 208', 'Traffic Management and Accident Investigation', 2, '2nd', 3),
('0004', 'PE 202', 'Team Sports', 2, '2nd', 2),
('0004', 'FIL 102', 'Pagbasa at Pagsulat', 2, '2nd', 3),
-- Year 3, 1st Sem
('0004', 'CRIM 301', 'Criminal Evidence and Procedure', 3, '1st', 3),
('0004', 'CRIM 302', 'Correctional Administration', 3, '1st', 3),
('0004', 'CRIM 303', 'Criminal Detection and Investigation', 3, '1st', 3),
('0004', 'CRIM 304', 'Forensic Chemistry', 3, '1st', 3),
('0004', 'RES 301', 'Methods of Research', 3, '1st', 3),
('0004', 'ELEC 301', 'Cyber Crime Investigation', 3, '1st', 3),
-- Year 3, 2nd Sem
('0004', 'CRIM 305', 'Juvenile Delinquency and Juvenile Justice', 3, '2nd', 3),
('0004', 'CRIM 306', 'Probation and Parole Administration', 3, '2nd', 3),
('0004', 'CRIM 307', 'Fire Technology and Arson Investigation', 3, '2nd', 3),
('0004', 'CRIM 308', 'Forensic Medicine (Legal Medicine)', 3, '2nd', 3),
('0004', 'ELEC 302', 'Crisis Management and Disaster Response', 3, '2nd', 3),
('0004', 'RIZAL 101', 'Life and Works of Rizal', 3, '2nd', 3),
-- Year 4, 1st Sem
('0004', 'CRIM 401', 'Thesis/Research 1', 4, '1st', 3),
('0004', 'CRIM 402', 'Industrial Security Management', 4, '1st', 3),
('0004', 'ELEC 401', 'Terrorism and Counter-Terrorism', 4, '1st', 3),
('0004', 'ELEC 402', 'Human Rights and International Law', 4, '1st', 3),
('0004', 'TECH 401', 'Security Entrepreneurship', 4, '1st', 3),
-- Year 4, 2nd Sem
('0004', 'CRIM 403', 'Thesis/Research 2', 4, '2nd', 3),
('0004', 'CRIM 404', 'Field Training/Practicum (600 Hours)', 4, '2nd', 6),
('0004', 'SEM 401', 'Criminology Board Review and Seminars', 4, '2nd', 3);

-- ══════════════════════════════════════════
-- BSED - Bachelor of Science in Education (Course ID: 0005)
-- ══════════════════════════════════════════
INSERT INTO curriculum (courseID, subject_code, description, year_level, semester, units) VALUES
-- Year 1, 1st Sem
('0005', 'EDUC 101', 'The Child and Adolescent Learners', 1, '1st', 3),
('0005', 'EDUC 102', 'The Teaching Profession', 1, '1st', 3),
('0005', 'EDUC 103', 'Foundation of Education', 1, '1st', 3),
('0005', 'ENG 101', 'Purposive Communication', 1, '1st', 3),
('0005', 'PE 101', 'Physical Fitness', 1, '1st', 2),
('0005', 'NSTP 101', 'National Service Training Program 1', 1, '1st', 3),
-- Year 1, 2nd Sem
('0005', 'EDUC 104', 'Facilitating Learner-Centered Teaching', 1, '2nd', 3),
('0005', 'EDUC 105', 'Technology for Teaching and Learning 1', 1, '2nd', 3),
('0005', 'EDUC 106', 'Principles of Teaching', 1, '2nd', 3),
('0005', 'ENG 102', 'The Contemporary World', 1, '2nd', 3),
('0005', 'PE 102', 'Rhythmic Activities', 1, '2nd', 2),
('0005', 'NSTP 102', 'National Service Training Program 2', 1, '2nd', 3),
-- Year 2, 1st Sem
('0005', 'EDUC 201', 'Assessment of Learning 1', 2, '1st', 3),
('0005', 'EDUC 202', 'The Teacher and the School Curriculum', 2, '1st', 3),
('0005', 'EDUC 203', 'Educational Psychology', 2, '1st', 3),
('0005', 'MATH 101', 'Mathematics in Modern World', 2, '1st', 3),
('0005', 'PE 201', 'Individual and Dual Sports', 2, '1st', 2),
('0005', 'FIL 101', 'Komunikasyon sa Akademikong Filipino', 2, '1st', 3),
-- Year 2, 2nd Sem
('0005', 'EDUC 204', 'Assessment of Learning 2', 2, '2nd', 3),
('0005', 'EDUC 205', 'Technology for Teaching and Learning 2', 2, '2nd', 3),
('0005', 'EDUC 206', 'Building and Enhancing Literacy Skills', 2, '2nd', 3),
('0005', 'SCI 201', 'Science, Technology and Society', 2, '2nd', 3),
('0005', 'PE 202', 'Team Sports', 2, '2nd', 2),
('0005', 'FIL 102', 'Pagbasa at Pagsulat', 2, '2nd', 3),
-- Year 3, 1st Sem
('0005', 'EDUC 301', 'Field Study 1 (Observation)', 3, '1st', 3),
('0005', 'EDUC 302', 'Curriculum Development', 3, '1st', 3),
('0005', 'EDUC 303', 'Special Education', 3, '1st', 3),
('0005', 'EDUC 304', 'Content and Pedagogy 1', 3, '1st', 3),
('0005', 'RES 301', 'Methods of Research', 3, '1st', 3),
('0005', 'ELEC 301', 'Values Education', 3, '1st', 3),
-- Year 3, 2nd Sem
('0005', 'EDUC 305', 'Field Study 2 (Participation)', 3, '2nd', 3),
('0005', 'EDUC 306', 'Classroom Management', 3, '2nd', 3),
('0005', 'EDUC 307', 'Content and Pedagogy 2', 3, '2nd', 3),
('0005', 'EDUC 308', 'Instructional Materials Development', 3, '2nd', 3),
('0005', 'ELEC 302', 'Multi-Grade Teaching', 3, '2nd', 3),
('0005', 'RIZAL 101', 'Life and Works of Rizal', 3, '2nd', 3),
-- Year 4, 1st Sem
('0005', 'EDUC 401', 'Action Research 1', 4, '1st', 3),
('0005', 'EDUC 402', 'Content and Pedagogy 3', 4, '1st', 3),
('0005', 'ELEC 401', 'Indigenous Peoples Education', 4, '1st', 3),
('0005', 'ELEC 402', 'Language and Literature for Teachers', 4, '1st', 3),
('0005', 'TECH 401', 'Edupreneurship', 4, '1st', 3),
-- Year 4, 2nd Sem
('0005', 'EDUC 403', 'Action Research 2', 4, '2nd', 3),
('0005', 'EDUC 404', 'Practice Teaching (Full Immersion)', 4, '2nd', 6),
('0005', 'SEM 401', 'Teacher Licensure Review and Seminars', 4, '2nd', 3);


-- ============================================
-- STEP 3: SEED ENROLLMENTS WITH REALISTIC DATA
-- ============================================

-- Define reusable arrays for random data
-- Grade options: 1.0, 1.25, 1.5, 1.75, 2.0, 2.25, 2.5, 2.75, 3.0, 5.0
-- Days: MON, TUE, WED, THU, FRI, MON/WED, TUE/THU, WED/FRI, MON/THU, SAT
-- Rooms: ROOM 101-405, LAB 1-4, LEC 1-3, AVR, GYM, LIBRARY

-- ══════════════════════════════════════════
-- STUDENT 1: SOPHIA GARCIA23 (BSIT - Full Progress to Year 4)
-- ══════════════════════════════════════════

-- Y1 S1 (2022-08-15)
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 1, CurriculumID, '2022-08-15' FROM curriculum WHERE courseID='0001' AND year_level=1 AND semester='1st';

-- Y1 S2 (2023-01-15)
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 1, CurriculumID, '2023-01-15' FROM curriculum WHERE courseID='0001' AND year_level=1 AND semester='2nd';

-- Y2 S1 (2023-08-15)
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 1, CurriculumID, '2023-08-15' FROM curriculum WHERE courseID='0001' AND year_level=2 AND semester='1st';

-- Y2 S2 (2024-01-15)
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 1, CurriculumID, '2024-01-15' FROM curriculum WHERE courseID='0001' AND year_level=2 AND semester='2nd';

-- Y3 S1 (2024-08-15)
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 1, CurriculumID, '2024-08-15' FROM curriculum WHERE courseID='0001' AND year_level=3 AND semester='1st';

-- Y3 S2 (2025-01-15)
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 1, CurriculumID, '2025-01-15' FROM curriculum WHERE courseID='0001' AND year_level=3 AND semester='2nd';

-- Y4 S1 (2025-08-15) - CURRENT
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 1, CurriculumID, '2025-08-15' FROM curriculum WHERE courseID='0001' AND year_level=4 AND semester='1st';


-- ══════════════════════════════════════════
-- STUDENT 2: DANIEL REYES23 (BSCS - Full Progress)
-- ══════════════════════════════════════════
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 2, CurriculumID, '2022-08-15' FROM curriculum WHERE courseID='0002' AND year_level=1 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 2, CurriculumID, '2023-01-15' FROM curriculum WHERE courseID='0002' AND year_level=1 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 2, CurriculumID, '2023-08-15' FROM curriculum WHERE courseID='0002' AND year_level=2 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 2, CurriculumID, '2024-01-15' FROM curriculum WHERE courseID='0002' AND year_level=2 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 2, CurriculumID, '2024-08-15' FROM curriculum WHERE courseID='0002' AND year_level=3 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 2, CurriculumID, '2025-01-15' FROM curriculum WHERE courseID='0002' AND year_level=3 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 2, CurriculumID, '2025-08-15' FROM curriculum WHERE courseID='0002' AND year_level=4 AND semester='1st';


-- ══════════════════════════════════════════
-- STUDENT 3: BIANCA SANTOS23 (BSHM)
-- ══════════════════════════════════════════
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 3, CurriculumID, '2022-08-15' FROM curriculum WHERE courseID='0003' AND year_level=1 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 3, CurriculumID, '2023-01-15' FROM curriculum WHERE courseID='0003' AND year_level=1 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 3, CurriculumID, '2023-08-15' FROM curriculum WHERE courseID='0003' AND year_level=2 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 3, CurriculumID, '2024-01-15' FROM curriculum WHERE courseID='0003' AND year_level=2 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 3, CurriculumID, '2024-08-15' FROM curriculum WHERE courseID='0003' AND year_level=3 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 3, CurriculumID, '2025-01-15' FROM curriculum WHERE courseID='0003' AND year_level=3 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 3, CurriculumID, '2025-08-15' FROM curriculum WHERE courseID='0003' AND year_level=4 AND semester='1st';


-- ══════════════════════════════════════════
-- STUDENT 4: PATRICK CRUZ23 (BSCRIM)
-- ══════════════════════════════════════════
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 4, CurriculumID, '2022-08-15' FROM curriculum WHERE courseID='0004' AND year_level=1 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 4, CurriculumID, '2023-01-15' FROM curriculum WHERE courseID='0004' AND year_level=1 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 4, CurriculumID, '2023-08-15' FROM curriculum WHERE courseID='0004' AND year_level=2 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 4, CurriculumID, '2024-01-15' FROM curriculum WHERE courseID='0004' AND year_level=2 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 4, CurriculumID, '2024-08-15' FROM curriculum WHERE courseID='0004' AND year_level=3 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 4, CurriculumID, '2025-01-15' FROM curriculum WHERE courseID='0004' AND year_level=3 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 4, CurriculumID, '2025-08-15' FROM curriculum WHERE courseID='0004' AND year_level=4 AND semester='1st';


-- ══════════════════════════════════════════
-- STUDENT 5: CLARISSE MENDOZA23 (BSED)
-- ══════════════════════════════════════════
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 5, CurriculumID, '2022-08-15' FROM curriculum WHERE courseID='0005' AND year_level=1 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 5, CurriculumID, '2023-01-15' FROM curriculum WHERE courseID='0005' AND year_level=1 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 5, CurriculumID, '2023-08-15' FROM curriculum WHERE courseID='0005' AND year_level=2 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 5, CurriculumID, '2024-01-15' FROM curriculum WHERE courseID='0005' AND year_level=2 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 5, CurriculumID, '2024-08-15' FROM curriculum WHERE courseID='0005' AND year_level=3 AND semester='1st';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 5, CurriculumID, '2025-01-15' FROM curriculum WHERE courseID='0005' AND year_level=3 AND semester='2nd';
INSERT INTO enrollments (student_id, subject_id, enrolled_at) 
SELECT 5, CurriculumID, '2025-08-15' FROM curriculum WHERE courseID='0005' AND year_level=4 AND semester='1st';


-- ============================================
-- STEP 4: ADD VARIED GRADES (Realistic Randomization)
-- ============================================

-- For past enrollments (before 2025-08-15), add grades
-- Using MySQL's RAND() for variation

-- Student 1 Grades (Sophia - BSIT) - Dean's Lister quality
INSERT INTO grades (enrollment_id, midterm, final)
SELECT e.id,
    ELT(1 + FLOOR(RAND(e.id) * 5), '1.0', '1.25', '1.5', '1.25', '1.75'),
    ELT(1 + FLOOR(RAND(e.id * 2) * 5), '1.0', '1.25', '1.5', '1.0', '1.75')
FROM enrollments e WHERE e.student_id = 1 AND e.enrolled_at < '2025-08-15';

-- Student 2 Grades (Daniel - BSCS) - Good student
INSERT INTO grades (enrollment_id, midterm, final)
SELECT e.id,
    ELT(1 + FLOOR(RAND(e.id * 3) * 6), '1.5', '1.75', '2.0', '1.75', '2.25', '1.5'),
    ELT(1 + FLOOR(RAND(e.id * 4) * 6), '1.25', '1.5', '1.75', '2.0', '1.75', '1.5')
FROM enrollments e WHERE e.student_id = 2 AND e.enrolled_at < '2025-08-15';

-- Student 3 Grades (Bianca - BSHM) - Average student
INSERT INTO grades (enrollment_id, midterm, final)
SELECT e.id,
    ELT(1 + FLOOR(RAND(e.id * 5) * 7), '1.75', '2.0', '2.25', '2.5', '2.0', '1.75', '2.25'),
    ELT(1 + FLOOR(RAND(e.id * 6) * 7), '1.5', '1.75', '2.0', '2.25', '2.0', '1.75', '2.0')
FROM enrollments e WHERE e.student_id = 3 AND e.enrolled_at < '2025-08-15';

-- Student 4 Grades (Patrick - BSCRIM) - Struggling student
INSERT INTO grades (enrollment_id, midterm, final)
SELECT e.id,
    ELT(1 + FLOOR(RAND(e.id * 7) * 7), '2.0', '2.25', '2.5', '2.75', '3.0', '2.5', '2.25'),
    ELT(1 + FLOOR(RAND(e.id * 8) * 7), '1.75', '2.0', '2.25', '2.5', '2.75', '2.25', '2.0')
FROM enrollments e WHERE e.student_id = 4 AND e.enrolled_at < '2025-08-15';

-- Student 5 Grades (Clarisse - BSED) - Consistent student
INSERT INTO grades (enrollment_id, midterm, final)
SELECT e.id,
    ELT(1 + FLOOR(RAND(e.id * 9) * 5), '1.5', '1.75', '2.0', '1.75', '1.5'),
    ELT(1 + FLOOR(RAND(e.id * 10) * 5), '1.5', '1.75', '1.5', '2.0', '1.75')
FROM enrollments e WHERE e.student_id = 5 AND e.enrolled_at < '2025-08-15';


-- ============================================
-- STEP 5: ADD VARIED SCHEDULES (Different Days/Times/Rooms)
-- ============================================

-- Define schedule patterns per enrollment ID to create variety
INSERT INTO schedule (enrollment_id, day, time_in, time_out, room)
SELECT e.id,
    ELT(1 + (e.id % 10), 'MON', 'TUE', 'WED', 'THU', 'FRI', 'MON/WED', 'TUE/THU', 'WED/FRI', 'MON/THU', 'SAT'),
    ELT(1 + ((e.id + 3) % 8), '07:00 AM', '08:00 AM', '09:00 AM', '10:00 AM', '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM'),
    ELT(1 + ((e.id + 3) % 8), '10:00 AM', '11:00 AM', '12:00 PM', '01:00 PM', '04:00 PM', '05:00 PM', '06:00 PM', '07:00 PM'),
    ELT(1 + ((e.id + 7) % 15), 'ROOM 101', 'ROOM 102', 'ROOM 201', 'ROOM 202', 'ROOM 301', 'ROOM 302', 'ROOM 401', 'LAB 1', 'LAB 2', 'LAB 3', 'LAB 4', 'LEC 1', 'LEC 2', 'AVR', 'GYM')
FROM enrollments e;


-- ============================================
-- STEP 6: ADD PAYMENTS (Tuition & Miscellaneous Fee Structure)
-- Main Remarks: "Tuition Fee" and "Miscellaneous Fee"
-- ============================================

-- Student 1 Payments (Installment payer - separates Tuition and Misc)
INSERT INTO payments (student_id, year_level, semester, school_year, payment_date, remarks, cost, amount_paid) VALUES
-- Y1 S1: Paid Misc first, then Tuition in parts
(1, 1, '1st', '2022-2023', '2022-08-10', 'Miscellaneous Fee', 3500, 3500),
(1, 1, '1st', '2022-2023', '2022-08-10', 'Tuition Fee - Downpayment', 12500, 5000),
(1, 1, '1st', '2022-2023', '2022-10-15', 'Tuition Fee - 2nd Installment', 7500, 4000),
(1, 1, '1st', '2022-2023', '2022-12-05', 'Tuition Fee - Final Balance', 3500, 3500),
-- Y1 S2: Full payment both
(1, 1, '2nd', '2022-2023', '2023-01-10', 'Tuition Fee', 12500, 12500),
(1, 1, '2nd', '2022-2023', '2023-01-10', 'Miscellaneous Fee', 3500, 3500),
-- Y2 S1: Staggered payments
(1, 2, '1st', '2023-2024', '2023-08-12', 'Miscellaneous Fee', 4000, 4000),
(1, 2, '1st', '2023-2024', '2023-08-12', 'Tuition Fee - Downpayment', 13000, 5000),
(1, 2, '1st', '2023-2024', '2023-10-20', 'Tuition Fee - 2nd Installment', 8000, 4500),
(1, 2, '1st', '2023-2024', '2023-12-10', 'Tuition Fee - Final Balance', 3500, 3500),
-- Y2 S2: Full payment
(1, 2, '2nd', '2023-2024', '2024-01-08', 'Tuition Fee', 13000, 13000),
(1, 2, '2nd', '2023-2024', '2024-01-08', 'Miscellaneous Fee', 4000, 4000),
-- Y3 S1: Staggered
(1, 3, '1st', '2024-2025', '2024-08-10', 'Miscellaneous Fee', 4500, 4500),
(1, 3, '1st', '2024-2025', '2024-08-10', 'Tuition Fee - Downpayment', 13500, 5000),
(1, 3, '1st', '2024-2025', '2024-10-15', 'Tuition Fee - 2nd Installment', 8500, 5000),
(1, 3, '1st', '2024-2025', '2024-12-12', 'Tuition Fee - Final Balance', 3500, 3500),
-- Y3 S2: Full payment
(1, 3, '2nd', '2024-2025', '2025-01-10', 'Tuition Fee', 13500, 13500),
(1, 3, '2nd', '2024-2025', '2025-01-10', 'Miscellaneous Fee', 4500, 4500),
-- Y4 S1: Current semester - partial (still owing tuition balance)
(1, 4, '1st', '2025-2026', '2025-08-10', 'Miscellaneous Fee', 5000, 5000),
(1, 4, '1st', '2025-2026', '2025-08-10', 'Tuition Fee - Downpayment', 14000, 5000),
(1, 4, '1st', '2025-2026', '2025-10-18', 'Tuition Fee - 2nd Installment', 9000, 4000);

-- Student 2 Payments (Full payer - pays both Tuition & Misc together)
INSERT INTO payments (student_id, year_level, semester, school_year, payment_date, remarks, cost, amount_paid) VALUES
(2, 1, '1st', '2022-2023', '2022-08-15', 'Tuition Fee', 13000, 13000),
(2, 1, '1st', '2022-2023', '2022-08-15', 'Miscellaneous Fee', 3500, 3500),
(2, 1, '2nd', '2022-2023', '2023-01-12', 'Tuition Fee', 13000, 13000),
(2, 1, '2nd', '2022-2023', '2023-01-12', 'Miscellaneous Fee', 3500, 3500),
(2, 2, '1st', '2023-2024', '2023-08-14', 'Tuition Fee', 14000, 14000),
(2, 2, '1st', '2023-2024', '2023-08-14', 'Miscellaneous Fee', 3500, 3500),
(2, 2, '2nd', '2023-2024', '2024-01-10', 'Tuition Fee', 14000, 14000),
(2, 2, '2nd', '2023-2024', '2024-01-10', 'Miscellaneous Fee', 3500, 3500),
(2, 3, '1st', '2024-2025', '2024-08-12', 'Tuition Fee', 15000, 15000),
(2, 3, '1st', '2024-2025', '2024-08-12', 'Miscellaneous Fee', 3500, 3500),
(2, 3, '2nd', '2024-2025', '2025-01-13', 'Tuition Fee', 15000, 15000),
(2, 3, '2nd', '2024-2025', '2025-01-13', 'Miscellaneous Fee', 3500, 3500),
-- Y4 S1: Paid misc full, tuition partial
(2, 4, '1st', '2025-2026', '2025-08-15', 'Miscellaneous Fee', 4000, 4000),
(2, 4, '1st', '2025-2026', '2025-08-15', 'Tuition Fee - Downpayment', 15000, 8000),
(2, 4, '1st', '2025-2026', '2025-10-25', 'Tuition Fee - Balance', 7000, 7000);

-- Student 3 Payments (Full payer each semester)
INSERT INTO payments (student_id, year_level, semester, school_year, payment_date, remarks, cost, amount_paid) VALUES
(3, 1, '1st', '2022-2023', '2022-08-18', 'Tuition Fee', 11500, 11500),
(3, 1, '1st', '2022-2023', '2022-08-18', 'Miscellaneous Fee', 3500, 3500),
(3, 1, '2nd', '2022-2023', '2023-01-16', 'Tuition Fee', 11500, 11500),
(3, 1, '2nd', '2022-2023', '2023-01-16', 'Miscellaneous Fee', 3500, 3500),
(3, 2, '1st', '2023-2024', '2023-08-17', 'Tuition Fee', 12500, 12500),
(3, 2, '1st', '2023-2024', '2023-08-17', 'Miscellaneous Fee', 3500, 3500),
(3, 2, '2nd', '2023-2024', '2024-01-15', 'Tuition Fee', 12500, 12500),
(3, 2, '2nd', '2023-2024', '2024-01-15', 'Miscellaneous Fee', 3500, 3500),
(3, 3, '1st', '2024-2025', '2024-08-16', 'Tuition Fee', 13500, 13500),
(3, 3, '1st', '2024-2025', '2024-08-16', 'Miscellaneous Fee', 3500, 3500),
(3, 3, '2nd', '2024-2025', '2025-01-14', 'Tuition Fee', 13500, 13500),
(3, 3, '2nd', '2024-2025', '2025-01-14', 'Miscellaneous Fee', 3500, 3500),
(3, 4, '1st', '2025-2026', '2025-08-18', 'Tuition Fee', 14500, 14500),
(3, 4, '1st', '2025-2026', '2025-08-18', 'Miscellaneous Fee', 3500, 3500);

-- Student 4 Payments (Pays Misc first, then Tuition later same week)
INSERT INTO payments (student_id, year_level, semester, school_year, payment_date, remarks, cost, amount_paid) VALUES
(4, 1, '1st', '2022-2023', '2022-08-10', 'Miscellaneous Fee', 3500, 3500),
(4, 1, '1st', '2022-2023', '2022-08-12', 'Tuition Fee', 12500, 12500),
(4, 1, '2nd', '2022-2023', '2023-01-09', 'Miscellaneous Fee', 3500, 3500),
(4, 1, '2nd', '2022-2023', '2023-01-11', 'Tuition Fee', 12500, 12500),
(4, 2, '1st', '2023-2024', '2023-08-11', 'Miscellaneous Fee', 3500, 3500),
(4, 2, '1st', '2023-2024', '2023-08-14', 'Tuition Fee', 13500, 13500),
(4, 2, '2nd', '2023-2024', '2024-01-08', 'Miscellaneous Fee', 3500, 3500),
(4, 2, '2nd', '2023-2024', '2024-01-10', 'Tuition Fee', 13500, 13500),
(4, 3, '1st', '2024-2025', '2024-08-09', 'Miscellaneous Fee', 4000, 4000),
(4, 3, '1st', '2024-2025', '2024-08-12', 'Tuition Fee', 14000, 14000),
(4, 3, '2nd', '2024-2025', '2025-01-10', 'Miscellaneous Fee', 4000, 4000),
(4, 3, '2nd', '2024-2025', '2025-01-13', 'Tuition Fee', 14000, 14000),
-- Y4 S1: Still has tuition balance
(4, 4, '1st', '2025-2026', '2025-08-11', 'Miscellaneous Fee', 4500, 4500),
(4, 4, '1st', '2025-2026', '2025-08-14', 'Tuition Fee - Downpayment', 14500, 10000),
(4, 4, '1st', '2025-2026', '2025-11-05', 'Tuition Fee - Balance', 4500, 4500);

-- Student 5 Payments (Consistent full payer)
INSERT INTO payments (student_id, year_level, semester, school_year, payment_date, remarks, cost, amount_paid) VALUES
(5, 1, '1st', '2022-2023', '2022-08-20', 'Tuition Fee', 11000, 11000),
(5, 1, '1st', '2022-2023', '2022-08-20', 'Miscellaneous Fee', 3500, 3500),
(5, 1, '2nd', '2022-2023', '2023-01-18', 'Tuition Fee', 11000, 11000),
(5, 1, '2nd', '2022-2023', '2023-01-18', 'Miscellaneous Fee', 3500, 3500),
(5, 2, '1st', '2023-2024', '2023-08-21', 'Tuition Fee', 12000, 12000),
(5, 2, '1st', '2023-2024', '2023-08-21', 'Miscellaneous Fee', 3500, 3500),
(5, 2, '2nd', '2023-2024', '2024-01-19', 'Tuition Fee', 12000, 12000),
(5, 2, '2nd', '2023-2024', '2024-01-19', 'Miscellaneous Fee', 3500, 3500),
(5, 3, '1st', '2024-2025', '2024-08-19', 'Tuition Fee', 13000, 13000),
(5, 3, '1st', '2024-2025', '2024-08-19', 'Miscellaneous Fee', 3500, 3500),
(5, 3, '2nd', '2024-2025', '2025-01-17', 'Tuition Fee', 13000, 13000),
(5, 3, '2nd', '2024-2025', '2025-01-17', 'Miscellaneous Fee', 3500, 3500),
(5, 4, '1st', '2025-2026', '2025-08-18', 'Tuition Fee', 14000, 14000),
(5, 4, '1st', '2025-2026', '2025-08-18', 'Miscellaneous Fee', 3500, 3500);


-- ============================================
-- VERIFICATION QUERIES (Run these to check)
-- ============================================
-- SELECT 'Curriculum Count by Course' as Info;
-- SELECT c.course_code, COUNT(*) as subjects FROM curriculum cur JOIN course c ON cur.courseID = c.courseID GROUP BY c.course_code;

-- SELECT 'Enrollment Count by Student' as Info;
-- SELECT s.lastname, COUNT(*) as enrollments FROM enrollments e JOIN students s ON e.student_id = s.id GROUP BY s.id;

-- SELECT 'Grade Distribution Sample' as Info;
-- SELECT midterm, COUNT(*) as count FROM grades GROUP BY midterm ORDER BY midterm;

-- SELECT 'Schedule Room Distribution' as Info;
-- SELECT room, COUNT(*) as count FROM schedule GROUP BY room ORDER BY count DESC LIMIT 10;
