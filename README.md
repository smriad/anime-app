
# Readme 
 
### Clone the Repository  

```bash
git clone https://github.com/your-username/anime-api.git  
```
### Navigate to the project directory:

```bash
cd anime-api   `
```
### Install PHP Dependencies

Install the required PHP dependencies:

```bash
composer install   
```

### Set Up Environment File

Copy .env.example to .env

```bash
cp .env.example .env
```

Change .env according your mysql setup - 

DB_HOST=127.0.0.1

DB_PORT=3306
DB_DATABASE=anime_db

DB_USERNAME=root

DB_PASSWORD=  `

### Generate Application Key
```bash
php artisan key:generate  
```

### Set Up the Database
```bash
php artisan migrate   `
```
### Import Anime Data

To import anime data from the Jikan API:
```bash
php artisan anime:import   `
```
### Run the Laravel Development Server

Start the Laravel development server:
```bash
php artisan serve   `
```
The application will be available at http://127.0.0.1:8000.

### Test the API

You can test the API by accessing the anime data:

curl http://127.0.0.1:8000/api/anime/{slug}   `

Replace {slug} with the actual anime slug.
