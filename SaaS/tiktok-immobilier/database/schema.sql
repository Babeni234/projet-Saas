CREATE DATABASE IF NOT EXISTS immotok CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE immotok;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    type ENUM('admin','enterprise','particulier') DEFAULT 'particulier',
    phone VARCHAR(20) DEFAULT NULL,
    website VARCHAR(255) DEFAULT NULL,
    verified BOOLEAN DEFAULT FALSE,
    cover_color VARCHAR(50) DEFAULT '#0a0f2e',
    followers_count INT DEFAULT 0,
    following_count INT DEFAULT 0,
    properties_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(15,0) DEFAULT 0,
    price_label VARCHAR(50) DEFAULT NULL,
    type ENUM('villa','appartement','studio','bureau','commerce','terrain') DEFAULT 'villa',
    transaction ENUM('vente','location') DEFAULT 'vente',
    surface INT DEFAULT 0,
    rooms INT DEFAULT 0,
    bathrooms INT DEFAULT 0,
    city VARCHAR(100) DEFAULT NULL,
    neighborhood VARCHAR(100) DEFAULT NULL,
    features TEXT DEFAULT NULL,
    tags TEXT DEFAULT NULL,
    video_url VARCHAR(255) DEFAULT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL,
    music_title VARCHAR(100) DEFAULT NULL,
    music_artist VARCHAR(100) DEFAULT NULL,
    status ENUM('active','inactive','sold') DEFAULT 'active',
    views INT DEFAULT 0,
    likes_count INT DEFAULT 0,
    comments_count INT DEFAULT 0,
    saves_count INT DEFAULT 0,
    shares_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_type (type),
    INDEX idx_transaction (transaction),
    INDEX idx_city (city),
    FULLTEXT idx_search (title, description, city, neighborhood, tags)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    property_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (user_id, property_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    property_id INT NOT NULL,
    text TEXT NOT NULL,
    likes_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS follows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT NOT NULL,
    following_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_follow (follower_id, following_id),
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS saves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    property_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_save (user_id, property_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    from_user_id INT DEFAULT NULL,
    type ENUM('like','comment','follow','save','reservation','alert') DEFAULT NULL,
    property_id INT DEFAULT NULL,
    text TEXT DEFAULT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (username, email, password, full_name, type, verified, bio) VALUES
('admin', 'admin@immotok.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin ImmoTok', 'admin', TRUE, 'Administrateur de la plateforme'),
('prestige_immo', 'contact@prestige-immo.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Prestige Immobilier CI', 'enterprise', TRUE, 'L''excellence immobilière depuis 2005'),
('alpha_prop', 'info@alpha-properties.net', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Alpha Properties', 'enterprise', TRUE, 'Votre partenaire de confiance pour l''investissement immobilier'),
('kouame_asso', 'kouame.asso@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kouamé Assouman', 'particulier', FALSE, 'Propriétaire, vente directe sans intermédiaire'),
('fatou_diallo', 'fatou.d@outlook.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Fatou Diallo', 'particulier', FALSE, 'J''investis dans l''immobilier depuis 10 ans');
-- Password for all demo users: password
