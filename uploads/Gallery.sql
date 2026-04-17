CREATE TABLE uploads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT NOT NULL,
    file_type VARCHAR(100),
    uploaded_by INT,
    uploaded_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description TEXT,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE upload_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

ALTER TABLE uploads ADD COLUMN category_id INT;
ALTER TABLE uploads ADD FOREIGN KEY (category_id) REFERENCES upload_categories(id) ON DELETE SET NULL;