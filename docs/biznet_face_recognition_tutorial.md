# Tutorial Penggunaan Layanan AI/ML Face Recognition

## A. Pendahuluan

Layanan AI/ML Face Recognition Biznet Gio dapat digunakan untuk mengembangkan aplikasi berbasis pengenalan wajah. Sebelum menggunakan tutorial ini, pastikan Anda sudah berlangganan layanan Face Recognition.

### Topologi Sistem

Sistem Face Recognition terdiri dari 3 komponen utama:

- **Client** - Program/aplikasi Anda yang mengakses API dan memiliki FaceGallery
- **FaceGallery** - Kumpulan data pengguna dari suatu tempat/area (seperti database)
- **User** - Orang yang terdaftar dalam sistem untuk pengenalan wajah

## B. Konfigurasi dan Penggunaan

### 1. Persiapan Postman

**Download Postman:** [https://www.postman.com/downloads](https://www.postman.com/downloads/)

**Mendapatkan Token:**
1. Login ke [Portal Biznet Gio](https://portal.biznetgio.com)
2. Pilih **AI and ML** → **Face Recognition**
3. Klik nama service Anda
4. Salin Token ID untuk autentikasi API

### 2. Setting Headers

Untuk setiap request API, tambahkan header:
- **Key:** `Accesstoken`
- **Value:** `TOKEN_ANDA`

### 3. Client Endpoints

#### Get Counters - Cek Sisa Kuota

**Endpoint:** `GET https://fr.neoapi.id/risetai/face-api/client/get-counters`

**Body (JSON):**
```json
{
  "trx_id": "unique_alphanumeric_string"
}
```

**Response Sukses:**
```json
{
  "status": "success",
  "status_message": "Success",
  "remaining_limit": {
    "n_api_hits": 1000,
    "n_face": 500,
    "n_facegallery": 10
  }
}
```

**Response Gagal (Token Invalid):**
```json
{
  "status": "error",
  "status_message": "Access token not authorized"
}
```

### 4. Mengelola FaceGallery

#### #1 GET My Facegalleries - Lihat Daftar FaceGallery

**Endpoint:** `GET https://fr.neoapi.id/risetai/face-api/facegallery/my-facegalleries`

**Body:** Tidak diperlukan parameter body

**Response:**
```json
{
  "status": "success",
  "status_message": "Success",
  "facegallery_id": ["gallery1", "gallery2"]
}
```

#### #2 POST Create Facegallery - Buat FaceGallery Baru

**Endpoint:** `POST https://fr.neoapi.id/risetai/face-api/facegallery/create-facegallery`

**Body (JSON):**
```json
{
  "facegallery_id": "nama_gallery_baru",
  "trx_id": "unique_alphanumeric_string"
}
```

**Response:**
```json
{
  "status": "success",
  "status_message": "FaceGallery created successfully",
  "facegallery_id": "nama_gallery_baru"
}
```

#### #3 DELETE Delete Facegallery - Hapus FaceGallery

**Endpoint:** `DELETE https://fr.neoapi.id/risetai/face-api/facegallery/delete-facegallery`

**Body (JSON):**
```json
{
  "facegallery_id": "nama_gallery",
  "trx_id": "unique_alphanumeric_string"
}
```

#### #4 POST Enroll Face - Daftarkan User Baru

**Endpoint:** `POST https://fr.neoapi.id/risetai/face-api/facegallery/enroll-face`

**Body (JSON):**
```json
{
  "user_id": "unique_user_id", 
  "user_name": "Nama User",
  "facegallery_id": "nama_gallery",
  "image": "base64_encoded_image",
  "trx_id": "unique_alphanumeric_string"
}
```

**Parameter Penjelasan:**
- `user_id` - ID unik user (NIK, NIM, ID Karyawan, email)
- `user_name` - Nama lengkap user
- `facegallery_id` - Gallery yang sudah dibuat sebelumnya
- `image` - Gambar wajah dalam format base64 (JPG/PNG)
- `trx_id` - String unik untuk logging

**Catatan Image Encoding:**
- Gunakan tools online seperti [base64.guru](https://base64.guru/converter/encode/image) untuk convert gambar ke base64
- Pada implementasi nyata, capture dari webcam/camera langsung di-encode ke base64

**Response Sukses:**
```json
{
  "status": "success",
  "status_message": "Face enrolled successfully"
}
```

#### #5 GET List Faces - Lihat Daftar User Terdaftar

**Endpoint:** `GET https://fr.neoapi.id/risetai/face-api/facegallery/list-faces`

**Body (JSON):**
```json
{
  "facegallery_id": "nama_gallery",
  "trx_id": "unique_alphanumeric_string"
}
```

**Response:**
```json
{
  "status": "success",
  "status_message": "Success",
  "faces": [
    {
      "user_id": "user123",
      "user_name": "Nama User"
    }
  ]
}
```

## Tips Implementasi

1. **Token Management** - Pastikan token selalu disertakan di header setiap request
2. **Image Quality** - Gunakan foto wajah yang jelas dan berkualitas baik
3. **Unique IDs** - Pastikan `user_id` dan `trx_id` selalu unik
4. **Error Handling** - Selalu cek response status untuk menangani error
5. **Base64 Encoding** - Pada aplikasi real, implementasikan encoding langsung dari camera capture

## Troubleshooting

**Error 401 - Unauthorized:**
- Cek apakah token sudah benar di header
- Pastikan token masih valid/belum expired

**Error 400 - Bad Request:**
- Cek format JSON pada request body
- Pastikan semua parameter wajib sudah diisi

**Error 412 - Face Not Detected:**
- Gunakan foto wajah yang lebih jelas
- Pastikan wajah terlihat dengan baik di foto

## Support

Untuk bantuan lebih lanjut:
- Email: support@biznetgio.com  
- Telepon: (021) 5714567
- Knowledge Base: [kb.biznetgio.com](https://kb.biznetgio.com)