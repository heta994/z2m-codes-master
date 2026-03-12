# Z2M Codes - MySQL Database Setup

## Quick Setup (XAMPP)

1. **Start MySQL** in XAMPP Control Panel.

2. **Create the database** – Run the schema in phpMyAdmin or MySQL CLI:
   - Open http://localhost/phpmyadmin
   - Click "Import" or "SQL"
   - Paste/import the contents of `schema.sql`
   - Or run: `mysql -u root -p < schema.sql`

3. **Configure** in `config.php`:
   ```php
   define('USE_MYSQL', true);
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'z2m_codes');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // XAMPP default is empty
   ```

## Tables

| Table | Purpose |
|-------|---------|
| `codes` | Admin-added projects (replaces codes_added.json) |
| `submissions_pending` | Pending contributor submissions |

## Switch Back to File Mode

Set `USE_MYSQL` to `false` in `config.php` to use JSON files instead:
- `data/codes_added.json`
- `data/submissions_pending.json`
