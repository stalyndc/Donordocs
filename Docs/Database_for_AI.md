# Database Schema for DonorDocs

This document outlines the MySQL database schema for the DonorDocs project.

## Tables

### `users`

Stores user authentication and role information.

- `id`: Primary key, auto-incrementing integer.
- `email`: User's email, unique, up to 190 characters.
- `password_hash`: Hashed password for security, up to 255 characters.
- `role`: User's role, can be 'admin' or 'staff' (default).
- `created_at`: Timestamp of user creation, defaults to current time.

### `org_settings`

Stores organization-specific settings.

- `id`: Primary key, auto-incrementing integer.
- `org_name`: Name of the organization, up to 255 characters.
- `org_address`: Full address of the organization, text.
- `org_ein`: Employer Identification Number, up to 50 characters.
- `logo_path`: Path to the organization's logo file, up to 255 characters.
- `signature_path`: Path to the signature image file, up to 255 characters.
- `default_text`: Default text for receipts or communications, text.
- `timezone`: Timezone of the organization, defaults to 'America/New_York'.

### `donors`

Stores information about individual donors.

- `id`: Primary key, auto-incrementing integer.
- `name`: Donor's full name, up to 255 characters.
- `email`: Donor's email address, up to 255 characters.
- `address`: Donor's physical address, text.
- `created_at`: Timestamp of donor record creation, defaults to current time.

### `donations`

Stores details of individual donations.

- `id`: Primary key, auto-incrementing integer.
- `donor_id`: Foreign key referencing the `donors` table.
- `donation_date`: Date of the donation.
- `amount`: Donation amount, decimal with 10 total digits and 2 after the decimal point.
- `method`: Method of donation, can be 'cash', 'check', 'card', 'ach', or 'other' (default).
- `designation`: Specific fund or purpose of the donation, up to 255 characters.
- `notes`: Any additional notes about the donation, text.
- `receipt_number`: Unique receipt number, up to 50 characters.
- `pdf_path`: Path to the generated PDF receipt, up to 255 characters.
