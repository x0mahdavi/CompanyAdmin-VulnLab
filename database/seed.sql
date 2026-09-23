USE companyadmin_lab;

-- ============================================
-- Seed Stages
-- ============================================

INSERT INTO stages
    (stage_number, slug, name, difficulty, description)
VALUES
    (
        1,
        'stage01',
        'Employee Search',
        'Easy',
        'Investigate an employee search feature in the company admin panel.'
    ),
    (
        2,
        'stage02',
        'Admin Login',
        'Easy',
        'Investigate the authentication mechanism used by the administration portal.'
    ),
    (
        3,
        'stage03',
        'Employee Profiles',
        'Easy',
        'Review how employee profile records are accessed.'
    ),
    (
        4,
        'stage04',
        'Support Tickets',
        'Medium',
        'Investigate how support ticket comments are stored and displayed.'
    ),
    (
        5,
        'stage05',
        'Account Settings',
        'Medium',
        'Review sensitive account actions available through the administration panel.'
    ),
    (
        6,
        'stage06',
        'Document Upload',
        'Medium',
        'Investigate the employee document upload functionality.'
    ),
    (
        7,
        'stage07',
        'Document Viewer',
        'Medium',
        'Investigate how company documents are selected and displayed.'
    ),
    (
        8,
        'stage08',
        'Session Management',
        'Hard',
        'Investigate authentication and session handling throughout the application.'
    ),
    (
        9,
        'stage09',
        'Report Fetcher',
        'Hard',
        'Investigate the administration report retrieval functionality.'
    ),
    (
        10,
        'stage10',
        'Administration',
        'Hard',
        'Investigate how privileges and administrative functionality are controlled.'
    );


-- ============================================
-- Seed Lab User
-- ============================================

INSERT INTO users
    (username, password_hash, role)
VALUES
    (
        'trainee',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llCwR5Yw6w8y8v6YJ7C6',
        'user'
    );


-- ============================================
-- Seed Flags
-- ============================================
-- IMPORTANT:
-- These are temporary development hashes.
-- We will replace them with proper generated
-- flag hashes when implementing each stage.

INSERT INTO flags
    (stage_id, flag_hash)
SELECT id, SHA2('FLAG-STAGE01-DEVELOPMENT', 256)
FROM stages
WHERE stage_number = 1;

INSERT INTO flags
    (stage_id, flag_hash)
SELECT id, SHA2('FLAG-STAGE02-DEVELOPMENT', 256)
FROM stages
WHERE stage_number = 2;

INSERT INTO flags
    (stage_id, flag_hash)
SELECT id, SHA2('FLAG-STAGE03-DEVELOPMENT', 256)
FROM stages
WHERE stage_number = 3;

INSERT INTO flags
    (stage_id, flag_hash)
SELECT id, SHA2('FLAG-STAGE04-DEVELOPMENT', 256)
FROM stages
WHERE stage_number = 4;

INSERT INTO flags
    (stage_id, flag_hash)
SELECT id, SHA2('FLAG-STAGE05-DEVELOPMENT', 256)
FROM stages
WHERE stage_number = 5;

INSERT INTO flags
    (stage_id, flag_hash)
SELECT id, SHA2('FLAG-STAGE06-DEVELOPMENT', 256)
FROM stages
WHERE stage_number = 6;

INSERT INTO flags
    (stage_id, flag_hash)
SELECT id, SHA2('FLAG-STAGE07-DEVELOPMENT', 256)
FROM stages
WHERE stage_number = 7;

INSERT INTO flags
    (stage_id, flag_hash)
SELECT id, SHA2('FLAG-STAGE08-DEVELOPMENT', 256)
FROM stages
WHERE stage_number = 8;

INSERT INTO flags
    (stage_id, flag_hash)
SELECT id, SHA2('FLAG-STAGE09-DEVELOPMENT', 256)
FROM stages
WHERE stage_number = 9;

INSERT INTO flags
    (stage_id, flag_hash)
SELECT id, SHA2('FLAG-STAGE10-DEVELOPMENT', 256)
FROM stages
WHERE stage_number = 10;


-- ============================================
-- Stage 01 - Employees
-- ============================================

INSERT INTO stage01_employees
    (employee_code, full_name, department, email, job_title)
VALUES
    (
        'EMP-1001',
        'Alice Morgan',
        'Finance',
        'alice.morgan@companyadmin.local',
        'Financial Analyst'
    ),
    (
        'EMP-1002',
        'Daniel Carter',
        'Engineering',
        'daniel.carter@companyadmin.local',
        'Senior Software Engineer'
    ),
    (
        'EMP-1003',
        'Sophia Bennett',
        'Human Resources',
        'sophia.bennett@companyadmin.local',
        'HR Manager'
    ),
    (
        'EMP-1004',
        'Michael Brooks',
        'Security',
        'michael.brooks@companyadmin.local',
        'Security Analyst'
    ),
    (
        'EMP-1005',
        'Emma Wilson',
        'Operations',
        'emma.wilson@companyadmin.local',
        'Operations Coordinator'
    ),
    (
        'EMP-1006',
        'James Anderson',
        'Engineering',
        'james.anderson@companyadmin.local',
        'Backend Developer'
    );
