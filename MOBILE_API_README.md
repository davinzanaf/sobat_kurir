# Sobat Kurir Mobile API Starter

API mobile ditambahkan paralel dengan route web lama. Web desktop tetap menggunakan `routes/web.php`, sedangkan Flutter memakai `routes/api.php`.

## Install dependency

```bash
composer require laravel/sanctum
php artisan migrate
```

## Jalankan server untuk HP fisik

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Base URL dari HP: `http://IP-LAPTOP:8000/api/mobile`

## Endpoint awal

- `POST /api/mobile/login`
- `POST /api/mobile/register/customer`
- `POST /api/mobile/register/kurir`
- `GET /api/mobile/me`
- `POST /api/mobile/logout`
- `GET /api/mobile/customer/dashboard`
- `GET /api/mobile/customer/tarif/options`
- `POST /api/mobile/customer/cek-ongkir`
- `POST /api/mobile/customer/pesanan`
- `GET /api/mobile/customer/riwayat-pesanan`
- `GET /api/mobile/tracking/{kode_resi}`
- `GET /api/mobile/kurir/dashboard`
- `GET /api/mobile/kurir/tugas-baru`
- `POST /api/mobile/kurir/tugas-baru/{id}/ambil`
- `GET /api/mobile/kurir/pesanan-saya`
- `PATCH /api/mobile/kurir/pesanan-saya/{id}/status`

Header untuk endpoint yang butuh login:

```http
Authorization: Bearer TOKEN_LOGIN
Accept: application/json
```
