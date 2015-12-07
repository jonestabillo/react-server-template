# REACT Server Template
Sample React Server Project Template

##Building and Executing the Application
1. Make sure PHP Composer is installed on your development machine.
2. Run `php build.php` on the project's root directory.
3. The deployable assets can now be used in the `build` directory.
4. Set the directory location of the config file `sample.ini` in the `include_path` settings on `php.ini`.
5. Now go to `build/sample/public` and execute `php endpoint.php localhost 80`. You can put what ever host and port parameters based on your requirement.
6. To conform that the server is running got to your browser and hit a POST request on `http://localhost/sample/sampleAction`.

##Development
1. Make sure PHP Composer is installed on your development machine.
2. Run `composer install` on the project's root directory.