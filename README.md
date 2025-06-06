# Community Survey Web App

This simple PHP application provides a consent form and 59‑question survey. Responses are stored in a MySQL database and can be viewed with basic charts.

## Setup
1. Create a MySQL database and user. Update credentials in `config.php`.
2. Import `init.sql` to create the `responses` table.
3. Serve the PHP files with your web server (e.g., Apache or PHP built‑in server `php -S localhost:8000`).

## Files
- `index.php` – consent form entry page
- `survey.php` – survey with automatic saving on each answer
- `save_response.php` – AJAX endpoint to save individual fields
- `results.php` – displays a sample chart of collected data
- `styles.css` – basic styling
- `init.sql` – database schema
