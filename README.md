# CR Management System (PHP / MySQL / Bootstrap)

A lightweight Class Representative (CR) Management System built with:
- HTML, CSS, JavaScript, Bootstrap 5
- PHP (for backend)
- MySQL (database)
- Twilio WhatsApp API integration (for sending notifications)
- Cron script to send scheduled reminders

This package includes:
- `sql/schema.sql` — database schema and example data
- `public/` — web frontend and PHP files (index.php, students.php, assignments.php, etc.)
- `config.php` — central configuration (DB + Twilio)
- `lib/send_whatsapp.php` — reusable function to send WhatsApp messages via Twilio
- `cron/cron_send.php` — script to run periodically (cron) to push reminders
- `assets/` — CSS/JS assets (Bootstrap via CDN usage in templates)

## Quick setup (local machine)
1. Requirements:
   - PHP 8+ with `pdo_mysql` or `mysqli`
   - MySQL / MariaDB
   - A web server (Apache, Nginx) or use PHP built-in server for testing:
     ```
     php -S localhost:8000 -t public
     ```
2. Create database:
   - Import `sql/schema.sql` into your MySQL server:
     ```
     mysql -u root -p < sql/schema.sql
     ```
   - This will create database `crms` and sample tables.

3. Configure `config.php`:
   - Open `config.php` and enter your MySQL credentials.
   - To enable WhatsApp via Twilio, add your Twilio Account SID, Auth Token, and WhatsApp-enabled number.
   - If you don't have Twilio, the app still works for managing students/assignments; just disable Twilio in config.

4. Start the site:
   - Point your webserver document root to the `public/` folder.
   - Or run PHP built-in server:
     ```
     cd public
     php -S localhost:8000
     ```
   - Visit http://localhost:8000

5. Scheduling reminders:
   - Use the `cron/cron_send.php` script.
   - Example cron entry (runs every 15 minutes):
     ```
     */15 * * * * /usr/bin/php /path/to/cr-management-system/cron/cron_send.php >> /tmp/crms_cron.log 2>&1
     ```
   - The script will find assignments/quizzes with reminders set and send WhatsApp messages to listed recipients.

## WhatsApp Groups & Limitations
- Twilio's WhatsApp API cannot programmatically create WhatsApp *group chats* or post *into existing group chats* reliably.
- Workaround provided:
  - The system sends messages to individual student numbers (one-to-one) — this effectively broadcasts to all members.
  - If you require real groups, consider using a Node.js tool (`whatsapp-web.js` or `Baileys`) that controls a WhatsApp Web session — this is outside the current PHP stack. You can still use the exported contacts CSV from this app as input to such a Node tool.

## File map (important)
- `public/index.php` — Dashboard
- `public/students.php` — Add / Edit / List students (stores phone numbers)
- `public/assignments.php` — Add / Edit / List assignments/quizzes
- `lib/send_whatsapp.php` — Twilio helper
- `config.php` — Central config file
- `sql/schema.sql` — SQL to create DB and tables

## Security notes
- This is a sample project. For production:
  - Use prepared statements / parameterized queries (we use mysqli with simple escaping here for brevity)
  - Protect admin pages with authentication
  - Use HTTPS and secure storage for API keys (do not commit to public repos)

Enjoy — unzip and run. If you want, I can also convert this into a GitHub-ready repo with `.gitignore` and CI instructions.
