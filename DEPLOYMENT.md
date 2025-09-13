# CTU Danao HRMO - Deployment Guide

## 🚀 Deploy to Railway (Recommended)

### Step 1: Prepare Your Project
1. Make sure all files are committed to Git
2. Push to GitHub repository

### Step 2: Deploy to Railway
1. Go to [railway.app](https://railway.app)
2. Sign up with GitHub
3. Click "New Project"
4. Select "Deploy from GitHub repo"
5. Choose your repository
6. Railway will automatically detect Laravel and deploy

### Step 3: Configure Environment Variables
In Railway dashboard, add these environment variables:

```
APP_NAME=CTU Danao HRMO
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

DB_CONNECTION=mysql
DB_HOST=containers-us-west-xxx.railway.app
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your-db-password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hrmodanao@ctu.edu.ph
MAIL_FROM_NAME=CTU Danao HRMO
```

### Step 4: Add Database
1. In Railway dashboard, click "New"
2. Select "Database" → "MySQL"
3. Railway will provide connection details
4. Update environment variables with new DB details

### Step 5: Run Migrations
1. In Railway dashboard, go to your app
2. Click "Deployments" → "View Logs"
3. Run: `php artisan migrate --seed`

## 🌐 Alternative: Deploy to Vercel

### For Frontend Only (if you want to separate frontend/backend)
1. Go to [vercel.com](https://vercel.com)
2. Import your GitHub repository
3. Configure build settings for Laravel
4. Deploy

## 🔧 Local Development Setup

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL

### Installation
```bash
# Clone repository
git clone <your-repo-url>
cd capstone-app

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets
npm run build

# Start development server
php artisan serve
npm run dev
```

## 📱 Access Your Deployed App

Once deployed, your app will be available at:
- **Railway**: `https://your-app-name.railway.app`
- **Vercel**: `https://your-app-name.vercel.app`

## 🔐 Admin Access

After deployment, you can access the admin panel at:
- **Admin Login**: `https://your-app-name.railway.app/admin/login`
- **Email**: `admin@ctu.edu.ph`
- **Password**: `admin123`

## 📧 Email Configuration

For production email sending, configure SMTP settings in Railway environment variables.

## 🛠️ Troubleshooting

### Common Issues:
1. **Database Connection**: Check DB credentials in environment variables
2. **File Permissions**: Ensure storage directory is writable
3. **Asset Loading**: Run `npm run build` before deployment
4. **Cache Issues**: Run `php artisan config:clear` after deployment

### Support:
- Railway Documentation: https://docs.railway.app
- Laravel Deployment: https://laravel.com/docs/deployment
