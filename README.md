# Rooster app
This app is prepared only as a part of interview on Laravel programmer role
It's based on Laravel 10. 
Its also dockerized BUT keep in mind the docker settings are set only for testing/development purposes

## How to run
1. Download the repository to your local machine
2. copy file .env.example to the same location but under a different name: .env
3. Build and run docker containers. You can do it by using single command.
    a) enter to /docker directory
    b) run command: docker-compose -p rooster up -d --build

4. execute other commands to install dependencies
   
   docker exec -it roster_php composer install
   
   docker exec -it roster_php php artisan key:generate
   
   docker exec -it roster_php npm install
   
   docker exec -it roster_php npm run build
   
   docker exec -it roster_php php artisan migrate
   
   docker exec -it roster_php chown -R www-data:www-data /var/www/database
   
   docker exec -it roster_php chmod -R 775 /var/www/database
   
6. 


