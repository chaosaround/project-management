# Task management app

#### Architecture note
Since requirements are based primarily on tasks management and project functionality is optional, in this app you may create tasks without specifying a project and Task/Project Laravel resources are independent (not nested).

#### References
[Axios Request Wrapper by sheharyarn](https://gist.github.com/sheharyarn/7f43ef98c5363a34652e60259370d2cb)

#### Screenshots
Screenshots are available in the "screenshots" folder.

### Instructions on how to set up and deploy 

Make sure your web server meets the Laravel 6 requirements specified here: [Laravel installation](https://laravel.com/docs/6.x/installation).
 
This app requires MySQL 5.6+ server.

For dev environment you may use such tools as [Laravel Valet](https://laravel.com/docs/6.x/valet) or [Laravel Homestead](https://laravel.com/docs/6.x/homestead) 

#### Deployment process

1. Create .env file:
```
cp .env.example .env
```

2. Install Composer dependencies
```
composer install
```

3. Create new MySQL database and set your credentials in .env file. 

4. Run DB migrations:
```
php artisan migrate
```

4. Install frontend dependencies:
```
npm install
```

5. To compile the frontend assets for dev environment:
```
npm run dev
```

You may find more information about compiling assets (for production) here: [Compiling Assets (Mix)
](https://laravel.com/docs/6.x/mix) 
