# Porsche Cayenne – Interactive Journey Quiz Campaign

## 1. Overview
The **Porsche Cayenne Interactive Journey Quiz** is a high-performance marketing microsite designed for the Porsche Cayenne campaign. It guides visitors through an interactive driving personality quiz to discover their ideal journey, collects PDPA-compliant registrations, and provides campaign reporting through the existing admin dashboard.

The application strictly follows **DRY, KISS, and SOLID** principles with **zero Livewire dependencies**, using pure Laravel routes, Blade components, vanilla JavaScript, and modern Tailwind / CSS transitions.

---

## 2. Route Map

| HTTP Method | Route / URL | Controller Action | Description |
|---|---|---|---|
| `GET` | `/` | `PorscheController@index` | Main Porsche microsite (loads Landing Screen) |
| `GET` | `/register` | `PorscheController@registerView` | Direct access to the Porsche Registration Screen |
| `POST` | `/register` | `PorscheController@register` | Asynchronous registration submission & validation |
| `POST` | `/quiz/submit` | `PorscheController@submitQuiz` | Submits quiz answers, calculates scoring & result |
| `GET` | `/admin/login` | View `auth.admin-login` | Admin login page with dynamic branding |
| `POST` | `/admin/login` | `LoginController@authenticateAdmin` | Authenticates administrator |
| `GET` | `/admin` | `StationController@admin` | Administrator reporting dashboard & metrics |
| `GET` | `/admin/users/export` | `StationController@exportUsers` | Exports registered campaign leads to CSV |
| `GET` | `/porsche` | Redirect | Redirects to root `/` |

---

## 3. Dynamic Environment Branding

The admin login screen and site-wide branding components dynamically adapt to `.env` settings:

```env
# Campaign Title & Identity
APP_NAME="Porsche Cayenne"

# Dynamic Logo (site-wide and admin login)
APP_LOGO="porsche/images/PORSCHE_logo_2x.webp"

# Dynamic Admin Login Backdrop
APP_BACKGROUND="porsche/images/01 PORSCHE_landing_2x.webp"

# Optional External Privacy Policy Override
PORSCHE_PRIVACY_POLICY_URL="https://www.porsche.com/malaysia/en/privacy-policy/"
```

- **Title**: Displayed as `Welcome to {APP_NAME} Admin Panel` on `/admin/login`.
- **Card Background**: Left panel of `/admin/login` dynamically renders `{APP_BACKGROUND}`.
- **Branding Logo**: Used site-wide via `resources/views/components/branding.blade.php` with `{APP_LOGO}`.

---

## 4. Admin Access & Credentials

The admin dashboard is protected by Spatie Role-Based Access Control (`role: admin`).

- **Dashboard URL**: [https://rohtov2.test/admin](https://rohtov2.test/admin)
- **Login URL**: [https://rohtov2.test/admin/login](https://rohtov2.test/admin/login)
- **Primary Admin**:
  - **Email**: `superadmin@gmail.com`
  - **Password**: `SuperAdmin123!`
- **Alternative Accounts**:
  - `manager@gmail.com` / `Manager123!`
  - `support@gmail.com` / `Support123!`

*To re-seed admin credentials if needed: `php artisan db:seed --class=AdminUserSeeder`*

---

## 5. Screen Progression & Layout

The microsite contains five sequential screens inside a single-page state engine:

1. **Screen 1 – Landing Page**: Left-aligned headline graphic and `Begin` button positioned in the upper sky above the Cayenne vehicle.
2. **Screen 2 – Registration Page**:
   - **Top Zone** (above the vehicle): Porsche logo and input fields (`Name`, `Contact Number`, `Email address`) with rounded 6px outline styling and icons.
   - **Bottom Zone** (below the vehicle): PDPA consent checkbox with link to Privacy Policy and centered `Continue` button.
3. **Screen 3 – Instructions Page**: Clear left-aligned instructions and `Next` button in the sky.
4. **Screen 4 – Interactive Quiz (4 Questions)**:
   - Question text and options `A.`, `B.`, `C.` in rounded outline cards above the vehicle.
   - Clean centered `Continue` button anchored below the car.
5. **Screen 5 – Personalized Result (3 Results)**:
   - `YOUR NEXT JOURNEY IS:` header.
   - Center Cayenne vehicle framed inside the red Porsche shield.
   - Result title with 2px vertical red accent bar enclosing subtitle and description.
   - Left-aligned `Try again` button (restarts quiz without re-registering).

---

## 6. Quiz Scoring & Tie-Breaking Engine

Scoring is handled server-side in `App\Services\PorscheQuizService`:

- **Result A**: *The one with endless possibilities* (Predominantly option A choices)
- **Result B**: *The one that is an unforgettable experience* (Predominantly option B choices)
- **Result C**: *The one that becomes a memory worth keeping* (Predominantly option C choices)

### Tie-Breaking Algorithm
If multiple categories share the highest score (e.g., A=2, B=2, C=0):
- Randomly picks **only** among the tied highest categories (either A or B).
- Never selects a category with a lower score.

---

## 7. Database Architecture & Dual Sync

### Database Tables:
1. `porsche_registrations`:
   - `id`, `full_name`, `contact_number`, `email`, `consent_given`, `consent_timestamp`, `created_at`, `updated_at`.
2. `porsche_quiz_attempts`:
   - `id`, `porsche_registration_id`, `answers` (JSON), `score_a`, `score_b`, `score_c`, `result_type`, `created_at`, `updated_at`.

### User Table Synchronization:
Registrations are automatically upserted into the application's core `users` table (`fname`, `number`, `email`, `marketing`), ensuring compatibility with:
- Existing `/admin` metrics and user count.
- Existing `/admin/users/export` CSV export endpoint.

---

## 8. Asset Optimization & Performance

- **Payload Reduction**: Downscaled 3280×4720 print-resolution art assets to 1200px 2x retina WebP formats, reducing the total directory payload by **96.6%** (from **35.96 MB down to 1.24 MB**).
- **Instant Paint**: Critical assets (`fonts`, `landing_header`, `landing_bg`, `porsche_logo`) use `<link rel="preload">`.
- **Low-Priority Prefetch**: Step screens (`form_bg`, `instruction_bg`, `quiz_bg`) load silently via `<link rel="prefetch">`.
- **Original Backups**: Uncompressed raw master images are safely preserved in `public/porsche/images/originals/`.

---

## 9. Tablet / iPad Mode

- Fully responsive edge-to-edge layout across mobile, iPad (768px, 820px, 1024px), and desktop.
- Enhanced top contrast overlay gradient (`rgba(11, 12, 14, 0.72)`) prevents sky bright spots from reducing typography contrast.
- Background images anchored cleanly at `center bottom` to keep the Cayenne vehicle prominent across varying aspect ratios.
