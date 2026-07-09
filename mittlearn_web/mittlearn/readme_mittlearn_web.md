**System Requirements**

PHP: >= 8.1

Framework: Laravel ^10.x

Database: MySQL



**Installation Steps**

**1. Extract the Project -**Unzip the project into your local or server directory.



**2. Environment Setup -** Update the .env file with your database configuration:



DB\_DATABASE=your\_database

DB\_USERNAME=root

DB\_PASSWORD=



**3. Folder Permissions (Linux/Mac)**

**chmod -R 775 storage bootstrap/cache**



**4. Frontend Setup (**If the UI is not loading properly, run:)



**npm install**

**npm run dev**

**4.1. Run command for Link Storage**

php artisan storage:link

**5. Run the Project**

php artisan serve



**Open in browser:**



http://127.0.0.1:8000 Or configure a Virtual Host as needed.



**Important Notes**



Run the following commands for optimization:



php artisan config:cache

php artisan route:cache

php artisan view:cache

php artisan optimize


