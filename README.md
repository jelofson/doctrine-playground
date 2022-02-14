# doctrine-playground
Testing doctrine ORM


Clone the repo...    

`cd blog`

`composer update` 

Create a blog database and set user permissions as necessary.

copy the `src/Blog/settings-dist.php` to `src/Blog/settings.php` and modify as needed 

Issue a couple of commands 

`./vendor/bin/phinx migrate to create the tables in the DB` 
`./vendor/bin/phinx seed:run to seed the DB with some faker data` 

`cd html` 

`php -S 0.0.0.0:8000` 

Browse to localhost:8000


