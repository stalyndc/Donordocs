CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(190) UNIQUE,
password_hash VARCHAR(255),
role ENUM('admin','staff') DEFAULT 'staff',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE org_settings (
id INT AUTO_INCREMENT PRIMARY KEY,
org_name VARCHAR(255),
org_address TEXT,
org_ein VARCHAR(50),
logo_path VARCHAR(255),
signature_path VARCHAR(255),
default_text TEXT,
timezone VARCHAR(64) DEFAULT 'America/New_York'
);

CREATE TABLE donors (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255),
email VARCHAR(255),
address TEXT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE donations (
id INT AUTO_INCREMENT PRIMARY KEY,
donor_id INT,
donation_date DATE,
amount DECIMAL(10,2),
method ENUM('cash','check','card','ach','other') DEFAULT 'other',
designation VARCHAR(255),
notes TEXT,
receipt_number VARCHAR(50) UNIQUE,
pdf_path VARCHAR(255),
FOREIGN KEY (donor_id) REFERENCES donors(id)
);
