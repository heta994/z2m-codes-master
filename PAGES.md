# Z2M Codes - All Pages

## Public Pages (No Login Required)

| Page | URL | Description |
|------|-----|-------------|
| **Home** | `/` | Landing page with hero, categories, featured projects |
| **All Projects** | `/codes/category/projects` | Browse all Arduino & programming projects |
| **Category: Arduino Basics** | `/codes/category/arduino-basics` | Arduino basics projects |
| **Category: Sensors** | `/codes/category/sensors` | Sensor projects |
| **Category: Motors** | `/codes/category/motors` | Motors & servos projects |
| **Category: LEDs** | `/codes/category/leds` | LEDs & display projects |
| **Category: IoT** | `/codes/category/iot` | IoT projects |
| **Category: Communication** | `/codes/category/communication` | Communication projects |
| **Search** | `/codes/search/{keyword}` | Search projects by keyword |
| **Difficulty Filter** | `/codes/difficulty/{beginner\|intermediate\|advanced}` | Filter by difficulty |
| **View Project** | `/codes/category/{category}/{slug}` | Single project with code, e.g. `/codes/category/arduino-basics/blink-led` |

---

## User Auth Pages

| Page | URL | Description |
|------|-----|-------------|
| **Sign Up** | `/signup` | Create new account |
| **Log In** | `/login` | User login |
| **Log Out** | `/logout` | User logout |

---

## Submit (Contributor)

| Page | URL | Description |
|------|-----|-------------|
| **Submit Project** | `/submit` | Submit a project (no login required) |
| **Submit Success** | `/submit/success` | Confirmation after submission |

---

## Admin Pages (Admin Login Required)

| Page | URL | Description |
|------|-----|-------------|
| **Admin Login** | `/admin/login.php` | Admin panel login |
| **Admin Dashboard** | `/admin/` | Overview, admin-added projects |
| **Add Project** | `/admin/add.php` | Add new project |
| **Edit Project** | `/admin/edit.php?id={id}` | Edit admin-added project |
| **Delete Project** | `/admin/delete.php?id={id}` | Delete project |
| **Submissions** | `/admin/submissions.php` | Review pending submissions |
| **Review Submission** | `/admin/review.php?id={id}` | Approve/reject submission |
| **Admin Logout** | `/admin/logout.php` | Admin logout |

---

## Quick URL Reference

**Local (PHP server):** `http://localhost:8080/`  
**Local (Apache):** `http://localhost/z2m-codes-master/z2m-codes-master/`  
**Production:** `https://z2m.org/` (or your domain)

### Example URLs
- Home: `http://localhost:8080/`
- Sign Up: `http://localhost:8080/signup`
- Log In: `http://localhost:8080/login`
- All Projects: `http://localhost:8080/codes/category/projects`
- Blink LED: `http://localhost:8080/codes/category/arduino-basics/blink-led`
- Admin: `http://localhost:8080/admin/`
