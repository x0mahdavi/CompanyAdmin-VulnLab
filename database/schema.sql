USE companyadmin_lab;

-- ============================================
-- Core Users
-- ============================================

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- Stages
-- ============================================

CREATE TABLE stages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    stage_number TINYINT UNSIGNED NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    difficulty ENUM('Easy', 'Medium', 'Hard') NOT NULL,
    description TEXT,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- Progress
-- ============================================

CREATE TABLE progress (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    stage_id INT UNSIGNED NOT NULL,
    completed BOOLEAN NOT NULL DEFAULT FALSE,
    completed_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_progress_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_progress_stage
        FOREIGN KEY (stage_id)
        REFERENCES stages(id)
        ON DELETE CASCADE,

    CONSTRAINT uq_progress_user_stage
        UNIQUE (user_id, stage_id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- Flags
-- ============================================

CREATE TABLE flags (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    stage_id INT UNSIGNED NOT NULL UNIQUE,
    flag_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_flags_stage
        FOREIGN KEY (stage_id)
        REFERENCES stages(id)
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
