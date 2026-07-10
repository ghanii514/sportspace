# SportSpace — AGENTS.md

## Stack
- **Framework**: CodeIgniter 4 + myth/auth (email activation, role filter)
- **DB**: MySQL via .env (`database.default.*`)
- **PHP**: ^8.1
- **Testing**: PHPUnit 10.5 (vendor/bin/phpunit)

## Commands
```sh
composer test                     # run all tests
vendor\bin\phpunit                # same
php spark                         # CI4 CLI tools (migrate, make:model, etc.)
```

## Key structure
```
app/
  Config/Routes.php      # routing
  Config/Database.php    # test DB defaults to SQLite3 :memory:
  Controllers/           # Admin, Booking, Chat, Field, Home, Owner, Promo, Riwayat, User
    Api/FieldApi.php     # JSON API for fields
  Models/                # FieldModel (table: lapangan), BookingModel, UserModel, PromoModel, etc.
  Views/                 # PHP templates, Bootstrap 5.3, layout: layout/template.php
  Database/
    Migrations/          # empty (no migrations yet)
    Seeds/               # empty
public/
  index.php              # front controller (not in root)
  img/fields/            # field image uploads
  img/user/              # user profile image uploads
```

## Routes
- `/` — Home (redirects admin→/admin, mitra→/owner)
- `admin/*` — role:admin filter for field/promo/booking CRUD
- `owner/*` — booked routes (owner dashboard, bookings, chat)
- `/login`, `/register` — myth/auth reserved routes
- `/chat/*` — user chat with owners
- `/booking/*` — booking flow (summary, save, payment, upload bukti)
- `/profile`, `/ganti-akun`, `/riwayat`, `/promo` — user features
- `/search?q=` — field search by name/address
- `api/*` — REST API routes (see below)

## Database conventions
- Table names in Indonesian: `lapangan` (fields), `booking`, `promo`
- Field image upload validation: max 1MB, jpg/jpeg/png, saved with `$img->getRandomName()`
- Booking statuses: pending, success, cancelled
- Payment: `bukti_bayar` upload, `pembayaran` column

## Testing quirks
- Tests bootstrap: `vendor/codeigniter4/framework/system/Test/bootstrap.php`
- DatabaseTestTrait uses SQLite3 `:memory:` by default (Config\Database::$tests)
- No migration/seeds fixtures exist yet (Seeds/ and Migrations/ are empty)
- Example tests at `tests/unit/HealthTest.php`, `tests/database/`, `tests/session/`

## REST API (`api/*`)
- Auth: JWT (firebase/php-jwt), token via `POST /api/auth/login`, expired 7 hari
- Public: `POST /api/auth/register`, `GET /api/fields`, `GET /api/fields/{id}`
- Protected: `GET /api/auth/me` (header `Authorization: Bearer <token>`)
- Input: semua endpoint POST/GET menerima JSON body (`Content-Type: application/json`) maupun form-urlencoded
- Gambar: response `image_url` sudah full URL (pakai `base_url()`)
- Filter: `app/Filters/JwtAuth.php`, terdaftar di `Config/Filters.php` sebagai `jwt`
- Helper: `json_response($data, $code, $message)` di `app/Helpers/api_helper.php`
- JWT secret di `.env` (`JWT_SECRET = ...`)

### API Endpoints

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|------|-----------|
| POST | `/api/auth/login` | - | Login, balikin JWT token |
| POST | `/api/auth/register` | - | Register akun baru |
| GET | `/api/auth/me` | JWT | Profil user login |
| GET | `/api/fields` | - | Daftar lapangan (`?q=` untuk search) |
| GET | `/api/fields/{id}` | - | Detail lapangan |
| GET | `/api/promos` | - | Daftar promo |
| GET | `/api/promos/{id}` | - | Detail promo |
| GET | `/api/booking/check-availability` | - | Cek slot tersedia (`?venue_id=&date=`) |
| POST | `/api/booking/check-promo` | - | Validasi kode promo |
| POST | `/api/booking` | JWT | Buat booking baru |
| GET | `/api/booking` | JWT | Riwayat booking user (`?status=completed`) |
| GET | `/api/booking/{id}` | JWT | Detail booking |
| POST | `/api/booking/{id}/upload-bukti` | JWT | Upload bukti bayar (multipart) |
| GET | `/api/chat/rooms` | JWT | Daftar chat room user |
| GET | `/api/chat/rooms/{id}/messages` | JWT | Pesan di room |
| POST | `/api/chat/send` | JWT | Kirim pesan |

## Notes
- Views mix PHP and Bootstrap 5.3 with inline CSS
- Auth helper (`logged_in()`, `user()`, `in_groups()`) provided by myth/auth
- Some duplicated code exists (e.g., FieldController search block duplicated)
- Account switching uses `localStorage` (`sportspace_accounts`)
- `.env` is tracked; contains SMTP credentials — do not expose
