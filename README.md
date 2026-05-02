# CTU Danao — HRMO Job Portal (Capstone)

**Capstone (CTU Danao):** Human Resource Management Office **job portal** for posting university jobs, managing **applications** and **interviews**, and running **admin** workflows. Includes applicant and admin **chat**, **QR-based** admin login flows, profile and **document** handling, and feature tests.

A comprehensive job application and hiring management system built with Laravel, featuring a modern UI, email notifications, and an admin dashboard.

## Features

### For Job Seekers
- **User Registration & Authentication** - Secure account creation and login
- **Job Browsing** - Search and filter job listings by department, position type, and keywords
- **Job Applications** - Apply for jobs with cover letter and resume upload
- **Application Tracking** - View application status and history
- **Email Notifications** - Receive updates on application status

### For Administrators
- **Admin Dashboard** - Comprehensive overview of applications, jobs, and interviews
- **Job Management** - Create, edit, and manage job postings
- **Application Review** - Review and manage job applications
- **Interview Scheduling** - Schedule interviews with candidates
- **Candidate Selection** - Approve or reject applications
- **Email Notifications** - Send status updates to candidates

## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL/SQLite
- **Email**: Laravel Mail (SMTP)
- **File Storage**: Local storage for resumes

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd capstone-app
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

## Configuration

### Email Configuration
Update your `.env` file with email settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### File Storage
Resumes are stored in `storage/app/public/resumes`. Make sure to create a symbolic link:

```bash
php artisan storage:link
```

## Usage

### Job Seeker Workflow
1. Register for an account
2. Browse available jobs
3. Apply for positions with cover letter and resume
4. Receive email confirmation
5. Track application status in dashboard

### Admin Workflow
1. Login to admin panel
2. Review job applications
3. Schedule interviews for approved candidates
4. Update application status
5. Send email notifications to candidates

## API Endpoints

### Public Routes
- `GET /` - Homepage
- `GET /jobs` - Job listings
- `GET /jobs/{job}` - Job details
- `GET /login` - Login page
- `GET /register` - Registration page

### Authenticated Routes
- `POST /jobs/{job}/apply` - Apply for job
- `GET /dashboard` - User dashboard
- `POST /logout` - Logout

### Admin Routes
- `GET /admin/login` - Admin login
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/jobs` - Manage jobs
- `GET /admin/applicants` - Manage applications
- `GET /admin/interviews/create` - Schedule interview

## Database Schema

### Jobs Table
- `id` - Primary key
- `title` - Job title
- `description` - Job description
- `department` - Department name
- `position_type` - Full-time, Part-time, Contract, Remote
- `salary` - Salary amount
- `application_deadline` - Application deadline
- `is_published` - Publication status
- `timestamps` - Created/updated timestamps

### Applicants Table
- `id` - Primary key
- `job_id` - Foreign key to jobs
- `name` - Applicant name
- `email` - Applicant email
- `phone` - Phone number
- `cover_letter` - Cover letter text
- `resume_path` - Path to uploaded resume
- `status` - pending, approved, rejected
- `timestamps` - Created/updated timestamps

### Interviews Table
- `id` - Primary key
- `applicant_id` - Foreign key to applicants
- `scheduled_at` - Interview date and time
- `notes` - Interview notes
- `status` - scheduled, completed, cancelled
- `timestamps` - Created/updated timestamps

## Security Features

- **CSRF Protection** - All forms protected against CSRF attacks
- **Input Validation** - Comprehensive validation on all inputs
- **File Upload Security** - Resume uploads restricted to PDF files
- **Authentication** - Secure user and admin authentication
- **Authorization** - Role-based access control

## Email Notifications

The system sends automated emails for:
- Application received confirmation
- Interview scheduling
- Application status updates (approved/rejected)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, email support@jobportal.com or create an issue in the repository.

## Changelog

### Version 1.0.0
- Initial release
- Job posting and application system
- Admin dashboard
- Email notifications
- Modern responsive UI