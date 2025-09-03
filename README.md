# Inspection Services Management System

A comprehensive Laravel-based inspection management system for handling various types of industrial inspections including lifting equipment, load testing, thorough examinations, MPI services, and visual inspections.

## Features

### Core Functionality
- **Multi-Service Inspections**: Support for lifting examination, load testing, thorough examination, MPI service, and visual inspection
- **Personnel Management**: Assign specific inspectors to each service type
- **Equipment Tracking**: Comprehensive equipment details and asset management
- **Client Management**: Detailed client information and project tracking
- **PDF Report Generation**: Professional inspection reports with images
- **Auto-Save**: Automatic saving of form data during inspection creation

### Inspection Types
1. **Lifting Examination**: First-time installations, safety assessments, defect identification
2. **Load Testing**: Test load application, duration tracking, pass/fail results
3. **Thorough Examination**: Initial, periodic, and post-installation examinations
4. **MPI Service**: Magnetic Particle Inspection with detailed procedures
5. **Visual Inspection**: Visual examination methods and observations

### Key Features
- **Inspector Assignment**: Each service can be assigned to a specific qualified inspector
- **Image Management**: Upload and attach inspection photos
- **Progress Tracking**: Real-time completion status
- **Status Management**: Draft, in-progress, completed, and approved statuses
- **Comprehensive Reporting**: Detailed PDF reports with all inspection data

## Technology Stack

- **Framework**: Laravel 11
- **Database**: MySQL
- **Frontend**: Bootstrap 5, JavaScript
- **PDF Generation**: DomPDF
- **Authentication**: Laravel built-in authentication
- **File Storage**: Local file system

## Installation

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL
- Apache/Nginx web server

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/waqastechie/inspection-services.git
   cd inspection-services
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   - Create a MySQL database
   - Update `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inspection_services
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed Database (Optional)**
   ```bash
   php artisan db:seed
   ```

7. **Storage Setup**
   ```bash
   php artisan storage:link
   ```

8. **Start Development Server**
   ```bash
   php artisan serve
   ```

## Usage

### Creating an Inspection

1. **Client Information**: Enter client details, project information, and inspection parameters
2. **Service Selection**: Choose which inspection services to perform
3. **Inspector Assignment**: Assign qualified inspectors to each service type
4. **Service Execution**: Complete the specific questions and tests for each service
5. **Image Upload**: Attach relevant inspection photos
6. **Report Generation**: Generate and download PDF reports

### User Roles

- **Super Admin**: Full system access, user management
- **Admin**: Create and manage inspections, generate reports
- **Inspector**: View assigned inspections, update inspection data
- **Viewer**: Read-only access to inspection reports

## API Endpoints

The system provides RESTful API endpoints for:
- Personnel management
- Equipment tracking
- Inspection CRUD operations
- Report generation

## Database Schema

### Main Tables
- `inspections`: Core inspection data
- `personnel`: Inspector and staff information
- `equipment`: Equipment and asset tracking
- `inspection_services`: Service-specific data
- `personnel_assignments`: Inspector-service assignments

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

## Security

- All user inputs are validated and sanitized
- CSRF protection enabled
- Authentication required for all operations
- Role-based access control implemented

## License

This project is proprietary software. All rights reserved.

## Support

For support and questions, please contact: waqastechie@gmail.com

## Version History

- **v1.0.0**: Initial release with core inspection functionality
- **v1.1.0**: Added inspector assignment features
- **v1.2.0**: Enhanced PDF reporting and image management

---

**Developed by**: Waqas Ahmed  
**GitHub**: [@waqastechie](https://github.com/waqastechie)
