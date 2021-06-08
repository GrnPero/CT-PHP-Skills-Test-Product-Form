# !/bin/bash
# To access the website in a Window environment 

echo "Now go to Window to access this website in your browser!";

php artisan cache:clear
php artisan config:clear

sudo php artisan serve --host 0.0.0.0 --port 8000
