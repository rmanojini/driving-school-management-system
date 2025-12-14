CREATE TABLE IF NOT EXISTS `admin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
);

-- Insert default admin if not exists (handling duplicate error via IGNORE)
INSERT IGNORE INTO `admin` (`id`, `username`, `password`) VALUES (1, 'admin', 'admin123');
