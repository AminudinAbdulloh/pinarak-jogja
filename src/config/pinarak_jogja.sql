-- Create Database
CREATE DATABASE IF NOT EXISTS pinarak_jogja;
USE pinarak_jogja;

-- Table: admins
CREATE TABLE admins (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: articles
CREATE TABLE articles (
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT NULL DEFAULT NULL,
    image VARCHAR(255) NULL DEFAULT NULL,
    author_id VARCHAR(255) NOT NULL,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: contacts
CREATE TABLE contacts (
    id INT(11) NOT NULL AUTO_INCREMENT,
    company_name VARCHAR(255) NOT NULL,
    phone_number VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    postal_code INT(11) NOT NULL,
    gmaps_embed_url TEXT NULL DEFAULT NULL,
    working_days VARCHAR(50) NULL DEFAULT NULL,
    working_time VARCHAR(50) NULL DEFAULT NULL,
    youtube VARCHAR(255) NULL DEFAULT NULL,
    -- linkedin VARCHAR(255) NULL DEFAULT NULL,
    instagram VARCHAR(255) NULL DEFAULT NULL,
    facebook VARCHAR(255) NULL DEFAULT NULL,
    tiktok VARCHAR(255) NULL DEFAULT NULL,
    -- twitter VARCHAR(255) NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: events
CREATE TABLE events (
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    start_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    location VARCHAR(255) NOT NULL,
    image VARCHAR(255) NULL DEFAULT NULL,
    author_id VARCHAR(255) NOT NULL,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: media_partners
CREATE TABLE media_partners (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    logo VARCHAR(255) NULL DEFAULT NULL,
    description TEXT NULL DEFAULT NULL,
    website VARCHAR(255) NULL DEFAULT NULL,
    author_id VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: profiles
CREATE TABLE profiles (
    id INT(11) NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    pass_photo VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL DEFAULT NULL,
    phone_number VARCHAR(50) NULL DEFAULT NULL,
    linkedin VARCHAR(255) NULL DEFAULT NULL,
    instagram VARCHAR(255) NULL DEFAULT NULL,
    facebook VARCHAR(255) NULL DEFAULT NULL,
    tiktok VARCHAR(255) NULL DEFAULT NULL,
    author_id VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: youtube_link
CREATE TABLE youtube_link (
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    url TEXT NOT NULL,
    author_id INT(11) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: tourist_objects
CREATE TABLE tourist_objects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    article VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    category ENUM('Nature', 'Culture', 'Culinary', 'Religious', 'Adventure', 'Historical') NOT NULL,
    address VARCHAR(255) NOT NULL,
    google_map_link VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
);

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `full_name`, `status`, `created_at`, `updated_at`) 
VALUES 
(2, 'admin1', 'admin1@gmail.com', '$2y$10$Y64QR1jWQ6aUtqE.KpAQ9uQI7FinZDFc/IuqMwUvluuunbn/FUWka', 'Administrator 1', 'active', NOW(), NOW());

INSERT INTO contacts (
    company_name,
    phone_number,
    email,
    address,
    city,
    postal_code,
    gmaps_embed_url,
    working_days,
    working_time,
    youtube,
    -- linkedin,
    instagram,
    facebook,
    tiktok,
    -- twitter
) VALUES (
    'PT Pinarak Jogja',
    '+62 812 3456 7890',
    'info@pinarakjogja.com',
    'Jl. Malioboro No.123, Yogyakarta',
    'Yogyakarta',
    55213,
    'https://www.google.com/maps/embed?pb=!1m18!...',
    'Senin - Jumat',
    '08.00 - 17.00',
    'https://youtube.com/@pinarakjogja',
    'https://instagram.com/pinarakjogja',
    'https://facebook.com/pinarakjogja',
    'https://tiktok.com/@pinarakjogja'
);

CREATE TABLE `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `logo_pinarak` VARCHAR(255) DEFAULT NULL,
  `logo_dinpar` VARCHAR(255) DEFAULT NULL,
  `banner` JSON DEFAULT NULL,
  `copyright` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `settings` (`logo_pinarak`, `logo_dinpar`, `banner`, `copyright`) 
VALUES (
  NULL, 
  NULL, 
  '["uploads/banners/banner1.jpg", "uploads/banners/banner2.jpg"]',
  '© 2025 Pinarak Jogja'
);