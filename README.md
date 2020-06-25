# Restful E-commerce API
<p align="center">
  <a href="https://laravel.com/" alt="Built with: Laravel v7.15.0">
    <img src="https://badgen.net/badge/Built%20with/Laravel%20v7.15.0/FF2D20" />
  </a>
  <a href="https://www.php.net/downloads.php" alt="Powered by: PHP v7.4.4">
    <img src="https://badgen.net/badge/Powered%20by/PHP%20v7.4.4/8892BF" />
  </a>
</p>

## Features
- All the registered **users** are stored in the **'users'** table.
- User can list **products** and acquire an additional role as a **seller**.
- Users can **purchase** the **products** as a **buyer**.
- All the user **transactions** are stored in the **'transaction'** table.
- The **product** information is stored in the **'products'** table.
- Each **product** belongs to a **category** listed in the **'catgories'** tables.  

## Installation and Requirements
1. Install [Composer](https://getcomposer.org/download/)
2. Install [Xampp](https://www.apachefriends.org/download.html)
3. Install [Postman](https://www.postman.com/downloads/)
4. Clone the repository.
5. Use [Composer](https://getcomposer.org/download/) to install the required dependencies by navigating to the root directory of the cloned repository and run the following command inside the Terminal:
```bash
composer install
``` 
6. Rename the **".env.example"** file in the root directory to **".env"**.
7. Generate the application key by running the following command:
```bash
php artisan key:generate
```

## Running the application
1. Navigate to the root directory and run the following command inside the Terminal:
```bash
php artisan serve
``` 
2. Open Xampp and verify PhpMyAdmin is running.
3. Create a new database with name ***"restful_api"*** using PhpMyAdmin.
4. Add the fake data by running the following commands inside the Terminal:
```bash
php artisan migrate
php artisan db:seed 
```
- ### NOTE: The directory **public/img** contains a few **image** files that are the part of fake data added by the migrations.
5. Use **Postman** to Import the **"Laravel E-commerce API.postman_collection.json"** file provided in the root directory to test the different API routes.

## LARAVEL / PHP / Other features used
- **Telescope**
- **Custom Service Classes**
- **Custom Facades**
- **Custom Exception Handling**
- **Validation and Custom Rules**
- **Migration and Seeding**
- **Scopes**
- **Mutators and Accessors**
- **Events**
- **Soft deletes**
- **Resources**
- **HATEOAS**
- **Reflection Classes**
- **Unit Testing**