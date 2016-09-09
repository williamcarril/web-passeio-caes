cd /var/www/tcc
sudo mkdir -p -- "vendor"
sudo chmod 777 vendor
sudo chmod 777 -R storage
sudo chmod 777 bootstrap/cache

sudo ln -svf /var/www/tcc/storage/app/repository /var/www/tcc/public/repository

composer install
php artisan migrate --force
