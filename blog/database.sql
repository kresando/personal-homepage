-- Create tables
CREATE TABLE IF NOT EXISTS posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    author VARCHAR(100) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS post_categories (
    post_id INT,
    category_id INT,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    PRIMARY KEY (post_id, category_id)
);

-- Insert categories
INSERT INTO categories (name, slug) VALUES
('Teknologi', 'teknologi'),
('AI', 'ai'),
('Web Development', 'web-development'),
('Mobile Development', 'mobile-development'),
('Keamanan', 'keamanan');

-- Insert posts (based on existing blog content)
INSERT INTO posts (title, content, image_url, created_at, author, slug) VALUES
(
    'Masa Depan AI dalam Pengembangan Web',
    'Kecerdasan buatan (AI) telah mengalami perkembangan yang sangat pesat dalam beberapa tahun terakhir, dan dampaknya terhadap industri pengembangan web sangat signifikan. Dari chatbot yang dapat berinteraksi dengan pengguna hingga sistem yang dapat menghasilkan kode secara otomatis, AI telah mengubah cara kita membangun, mengelola, dan berinteraksi dengan website.\n\nPengembangan web tradisional memerlukan waktu dan sumber daya yang signifikan. Developer harus menulis kode untuk setiap aspek website, dari frontend hingga backend. Namun, dengan kemunculan alat-alat berbasis AI, proses ini menjadi jauh lebih efisien.',
    'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
    '2025-04-12 10:00:00',
    'Kresando Seon',
    'masa-depan-ai-dalam-pengembangan-web'
),
(
    'Perbandingan Framework JavaScript Modern',
    'Analisis detail tentang React, Vue, dan Angular untuk membangun aplikasi web modern di tahun 2024. Dalam artikel ini kita akan membahas kelebihan dan kekurangan masing-masing framework serta kapan sebaiknya menggunakan framework tertentu untuk project Anda.',
    'https://images.unsplash.com/photo-1488590528505-98d2b5aba04b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
    '2025-04-13 11:00:00',
    'Kresando Seon',
    'perbandingan-framework-javascript-modern'
),
(
    'Tren Pengembangan Aplikasi Mobile 2025',
    'Menjelajahi teknologi terbaru dan praktik terbaik dalam pengembangan aplikasi untuk perangkat mobile. Dari Progressive Web Apps hingga Native Development, kita akan membahas berbagai pendekatan dalam pengembangan aplikasi mobile modern.',
    'https://images.unsplash.com/photo-1558346490-a72e53ae2d4f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
    '2025-04-14 12:00:00',
    'Kresando Seon',
    'tren-pengembangan-aplikasi-mobile-2025'
),
(
    'Keamanan Siber untuk Aplikasi Modern',
    'Langkah-langkah penting untuk mengamankan aplikasi web dan mobile Anda dari ancaman keamanan terbaru. Pembahasan meliputi best practices dalam pengamanan aplikasi, penanganan vulnerability, dan implementasi security measures.',
    'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
    '2025-04-15 13:00:00',
    'Kresando Seon',
    'keamanan-siber-untuk-aplikasi-modern'
);

-- Connect posts with categories
INSERT INTO post_categories (post_id, category_id) VALUES
(1, 1), -- AI in Web Dev -> Teknologi
(1, 2), -- AI in Web Dev -> AI
(1, 3), -- AI in Web Dev -> Web Development
(2, 1), -- JS Frameworks -> Teknologi
(2, 3), -- JS Frameworks -> Web Development
(3, 1), -- Mobile Trends -> Teknologi
(3, 4), -- Mobile Trends -> Mobile Development
(4, 1), -- Cybersecurity -> Teknologi
(4, 5); -- Cybersecurity -> Keamanan