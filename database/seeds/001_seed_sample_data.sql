-- Sample development data for DonorDocs
-- Run with: mysql -u USER -p donordocs < database/seeds/001_seed_sample_data.sql

INSERT INTO users (email, password_hash, role) VALUES
    ('admin@donordocs.test', '$2y$10$qkTz1AqNNvpBwhIQlEb9euW3d4361Gbtpj5r/sK5bjAo9fOCLKQdS', 'admin'),
    ('staff@donordocs.test', '$2y$10$qkTz1AqNNvpBwhIQlEb9euW3d4361Gbtpj5r/sK5bjAo9fOCLKQdS', 'staff');

INSERT INTO org_settings (org_name, org_address, org_ein, city, state, zip, signer_name, signer_title, default_receipt_text)
VALUES (
    'DonorDocs Test Org',
    '123 Compliance Lane\nAustin, TX 78701',
    '12-3456789',
    'Austin',
    'TX',
    '78701',
    'Jamie Rivera',
    'Executive Director',
    'No goods or services were provided in exchange for this contribution.'
);

INSERT INTO donors (name, email, address) VALUES
    ('Alex Johnson', 'alex.johnson@example.com', '45 Elm Street\nAustin, TX 78704'),
    ('Maria Chen', 'maria.chen@example.com', '78 Oak Avenue\nAustin, TX 78705');

INSERT INTO donations (donor_id, donation_date, amount, method, designation, notes, receipt_number, receipt_pdf_path)
VALUES
    (1, '2025-01-05', 250.00, 'card', 'General Fund', 'First-time donor', 'DD-2025-000001', 'storage/receipts/DD-2025-000001.pdf'),
    (2, '2025-01-12', 120.00, 'check', 'Scholarship Fund', NULL, 'DD-2025-000002', 'storage/receipts/DD-2025-000002.pdf');

INSERT INTO receipt_sequences (year, last_number) VALUES
    ('2025', 2)
ON DUPLICATE KEY UPDATE last_number = VALUES(last_number);
