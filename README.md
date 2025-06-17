# Face Recognition Attendance System

A comprehensive attendance management system built with Laravel that uses face recognition technology powered by Biznet Face API for secure and accurate employee attendance tracking.

## Features

### 🎯 Core Features
- **Face Recognition Authentication**: Secure attendance using Biznet Face API
- **Real-time Check-in/Check-out**: GPS location validation with configurable radius
- **Admin Dashboard**: Complete management interface for administrators
- **User Dashboard**: Intuitive interface for employees
- **Location Management**: Create and manage multiple attendance locations
- **Attendance History**: Detailed records with filtering and export capabilities
- **Mobile-Friendly**: Responsive design for mobile devices

### 👥 User Roles
- **Admin**: Full system access including location and user management
- **User**: Personal attendance management and face enrollment

### 📱 Mobile Features
- Camera integration for face capture
- GPS location detection
- Offline-capable attendance interface
- Real-time verification feedback

## Technology Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **Frontend**: Tailwind CSS 4, Alpine.js
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **Face Recognition**: Biznet Face API integration
- **Authentication**: Laravel Breeze with custom roles
- **Asset Building**: Vite

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (or MySQL/PostgreSQL)
- Biznet Face API account and credentials

### Step 1: Clone Repository

```bash
git clone <repository-url>
cd presensi-face-recognition
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Environment Variables

Edit your `.env` file with the following configurations:

```env
# Basic Laravel Configuration
APP_NAME="Face Recognition Attendance"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration (SQLite default)
DB_CONNECTION=sqlite
# For MySQL/PostgreSQL, configure accordingly:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=attendance_db
# DB_USERNAME=root
# DB_PASSWORD=

# Biznet Face API Configuration
BIZNET_FACE_BASE_URL=https://fr.neoapi.id/risetai/face-api
BIZNET_FACE_ACCESS_TOKEN=your_biznet_face_api_token_here
BIZNET_FACE_GALLERY_ID=attendance_system

# Mail Configuration (optional)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@yourcompany.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 5: Database Setup

```bash
# Create SQLite database file (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

### Step 6: Build Assets

```bash
# Build frontend assets
npm run build

# For development with hot reload
npm run dev
```

### Step 7: Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## Biznet Face API Setup

### 1. Get API Credentials

1. Visit [Biznet Gio Portal](https://portal.biznetgio.com)
2. Login to your account
3. Navigate to **AI and ML** > **Face Recognition**
4. Create a new Face Recognition Service
5. Copy your **Token ID** from the service details

### 2. Configure API

Update your `.env` file:

```env
BIZNET_FACE_ACCESS_TOKEN=your_actual_token_here
BIZNET_FACE_GALLERY_ID=your_company_attendance_system
```

### 3. Test API Connection

```bash
# Run tinker to test API connection
php artisan tinker

# Test API
$service = app(\App\Services\FaceRecognitionService::class);
$result = $service->getCounters();
dd($result);
```

## Default Accounts

After seeding, you can login with these accounts:

### Admin Account
- **Email**: admin@example.com
- **Password**: password
- **Role**: Administrator

### User Accounts
- **Email**: john@example.com, jane@example.com, bob@example.com
- **Password**: password
- **Role**: User

## System Architecture

### Face Recognition Flow

1. **Enrollment**: Users capture their face image through webcam
2. **Storage**: Face data is securely stored in Biznet Face API
3. **Verification**: During attendance, face is captured and verified against enrolled data
4. **Validation**: System checks confidence level and location proximity
5. **Recording**: Successful verification creates attendance record

### Location Validation

- Each location has configurable GPS coordinates and radius
- Users must be within the specified radius to check in/out
- Location validation can be disabled for remote work scenarios

### Database Schema

```
users
├── id, name, email, password
├── role (admin/user)
├── employee_id, phone
├── face_image, is_face_enrolled
└── face_gallery_id

locations
├── id, name, address
├── latitude, longitude, radius
└── is_active

attendances
├── id, user_id, location_id
├── type (check_in/check_out)
├── attendance_time
├── latitude, longitude
├── face_image, confidence_level
├── is_verified, notes
└── timestamps
```

## Usage Guide

### For Users

1. **Registration**: Create account with employee details
2. **Face Enrollment**:
    - Navigate to Face Recognition > Enroll Face
    - Follow on-screen instructions for optimal face capture
    - Ensure good lighting and face the camera directly
3. **Daily Attendance**:
    - Check In: Select location and capture face
    - Check Out: Capture face to complete attendance
4. **View History**: Access personal attendance records and statistics

### For Administrators

1. **Location Management**:
    - Create attendance locations with GPS coordinates
    - Set allowed radius for each location
    - Activate/deactivate locations as needed

2. **User Management**:
    - Monitor user face enrollment status
    - View all attendance records
    - Export reports for analysis

3. **System Monitoring**:
    - Dashboard overview of daily attendance
    - Real-time verification statistics
    - Location usage analytics

## API Endpoints

### Face Recognition
- `POST /face/enroll` - Enroll user face
- `POST /face/update` - Update enrolled face
- `POST /face/test-verification` - Test face verification
- `DELETE /face/delete` - Remove face enrollment

### Attendance
- `GET /attendance` - User attendance dashboard
- `GET /attendance/check-in` - Check-in interface
- `GET /attendance/check-out` - Check-out interface
- `POST /attendance/process` - Process attendance with face recognition
- `GET /attendance/history` - User attendance history

### Admin Routes
- `GET /admin/locations` - Location management
- `POST /admin/locations` - Create new location
- `GET /admin/attendance/history` - All attendance records
- `POST /admin/locations/{id}/toggle-status` - Toggle location status

## Security Features

- **Face Data Protection**: Face images stored securely in Biznet API
- **Location Verification**: GPS validation with configurable radius
- **Confidence Threshold**: Adjustable face recognition confidence levels
- **Role-based Access**: Separate admin and user permissions
- **Audit Trail**: Complete attendance history with timestamps
- **Data Encryption**: Secure transmission of face data to API

## Troubleshooting

### Common Issues

**Face Recognition Not Working**
- Verify Biznet API credentials in `.env`
- Check internet connection
- Ensure camera permissions are granted
- Try with better lighting conditions

**GPS Location Issues**
- Enable location services in browser
- Use HTTPS for production deployment
- Configure location coordinates correctly

**Database Connection**
- Verify database file exists (SQLite)
- Check database credentials (MySQL/PostgreSQL)
- Run `php artisan migrate:fresh --seed` if needed

**Asset Loading Issues**
- Run `npm run build` to compile assets
- Clear browser cache
- Check file permissions

### Debug Mode

Enable debug mode in `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Check logs in `storage/logs/laravel.log`

## Performance Optimization

### Production Deployment

1. **Environment Configuration**:
```env
APP_ENV=production
APP_DEBUG=false
```

2. **Cache Optimization**:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Database Optimization**:
```bash
php artisan migrate --force
php artisan db:seed --force
```

4. **Asset Optimization**:
```bash
npm run build
```

### Recommended Server Requirements

- **CPU**: 2+ cores
- **RAM**: 4GB minimum, 8GB recommended
- **Storage**: 20GB SSD
- **Network**: Stable internet for face API calls
- **SSL Certificate**: Required for camera access in production

## Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions:

- **Documentation**: Check this README and inline code comments
- **Biznet Face API**: [Biznet Gio Support](https://support.biznetgio.com/)
- **Laravel**: [Laravel Documentation](https://laravel.com/docs)

## Changelog

### Version 1.0.0
- Initial release with face recognition attendance
- Admin and user dashboards
- Location management system
- Mobile-responsive interface
- Biznet Face API integration
- Complete attendance history and reporting

---

**Note**: This system requires active Biznet Face API subscription for face recognition functionality. Ensure you have proper API credentials and sufficient quota for your organization's needs.
