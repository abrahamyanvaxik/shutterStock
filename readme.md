# To run this project you need to

- Rename ```.env.example``` file to ```.env``` inside your project root and fill the database information.
    ``` 
    DB_HOST=database
    DB_PORT=3306
    DB_DATABASE=db_name
    DB_USERNAME=db_username
    DB_PASSWORD=db_password

-  Fill the database information with fresh migrations and seeds ```php artisan migrate:fresh --seed```.