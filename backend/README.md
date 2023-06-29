# Directorio backend 
# navega desde la consola de comandos al directorio backend 
# Instala las dependencias con composer 
#$ composer install
# Crea la copia del archivo .env 
#$ cp .env.example .env
# Genera la api KEy
#$ php artisan key:generate
# Crear la base de datos riddle mysql
# Ejecuta las migraciones y los seeder
#$ php artisan migrate 
#$ php artisan migrate --seed
#Ejecuta el servidor de laravel 
#php artisan serve
# Ejecuta el servidor websockets
# php artisan websockets:serve
