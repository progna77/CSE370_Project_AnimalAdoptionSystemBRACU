# Campus Animal Adoption Center

A PHP + MySQL/MariaDB project for campus animal rescue, adoption, feeding, treatment, donations and expenses.

## Setup on Mac XAMPP

1. Put this folder inside `/Applications/XAMPP/xamppfiles/htdocs/` and name it `campus-animal-adoption`.
2. Start **Apache Web Server** and **MySQL Database** in XAMPP Manager.
3. Open `http://localhost/phpmyadmin`.
4. Import `database.sql`.
5. Open `http://localhost/campus-animal-adoption/`.
6. Create an account. Choose **Volunteer** if you need management actions such as handling cases, approving adoption requests, recording feedings/treatments/expenses.

## What was fixed from the original files/SQL

- Fixed `USER` / `users` table-name mismatch and `user_ID` / `user_id` mismatch.
- Fixed broken login SQL that had two `?` placeholders but only one bound value and tried to compare a password hash directly.
- Login now loads by username and uses `password_verify()` correctly.
- Passwords are stored with `password_hash()`.
- Added session regeneration and real dashboard redirects.
- Removed non-functional Google/GitHub login buttons rather than pretending OAuth works.
- Corrected SQL data types: dates are DATE/DATETIME, text is VARCHAR/TEXT, email is VARCHAR, money uses DECIMAL.
- Replaced multiple boolean-ish columns (`pending`,`approved`,`rejected`; `open`,`in_progress`,`closed`) with one status field.
- Removed spaces from table/column names and normalized naming.
- Added primary keys, AUTO_INCREMENT IDs, foreign keys and cascading rules.
- Added image upload validation for jpg/jpeg/png/webp.
- Added role-based access for volunteer-only management actions.
- Added complete modules: users, animals, status history, reports/case handling, adoptions, feeding notes, vets, treatment history, donations, expenses and donation usage.

## Note

This project uses the corrected schema in `database.sql`. Do not mix it with the old dump unless you manually migrate the data.
