# 1. Extract and navigate to the Laravel project
cd mittlearn_web/mittlearn

# 2. Install PHP dependencies
composer install

# 3. Configure environment
cp .env.example .env
# Edit .env with database credentials:
#   DB_DATABASE=your_database
#   DB_USERNAME=root
#   DB_PASSWORD=

# 4. Generate app key and cache configuration
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Setup front-end assets
npm install
npm run dev

# 6. Link storage for file uploads
php artisan storage:link

# 7. Serve locally
php artisan serve
# Open http://127.0.0.1:8000
