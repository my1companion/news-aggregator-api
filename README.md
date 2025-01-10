# Running News Aggregator without Docker
## Prerequisites
Before you begin, ensure you have the following software installed:

- PHP (>= 8.0)
- Composer
- Node.js & NPM (Optional, for frontend assets)

## Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/my1companion/news-aggregator-api.git
   cd news-aggregator-api
   ```
2. Install PHP dependencies
	Run Composer to install the required PHP dependencies:

   ```bash
   composer install
   ```
3. Set up the .env file
Copy the .env.example file to create a .env file:

   ```bash
   cp .env.example .env
   ```
Update the .env file to match your local environment configuration, such as database settings, mail configuration, and other environment variables.
   ```bash
   MAIL_MAILER=smtp
   MAIL_SCHEME=null
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_ENCRYPTION=tls
   MAIL_USERNAME=1339d66524b3f7
   MAIL_PASSWORD=559ccd495e2b66
   MAIL_FROM_ADDRESS="hello@newsaggregator.com"
   MAIL_FROM_NAME="${APP_NAME}"

   NEWSAPI_API_KEY="YOUR_NEWSAPI_API_KEY"
   GUARDIAN_API_KEY="YOUR_GUARDIAN_API_KEY"
   NYTIMES_API_KEY="YOUR_NYTIMES_API_KEY"

   L5_SWAGGER_GENERATE_ALWAYS=true
   L5_SWAGGER_API_VERSION=1.0.0
   L5_SWAGGER_BASE_PATH=/api
   L5_SWAGGER_TITLE="Your API Documentation"
   ```
Signup to get API KEY on NewsAPI, Gaurdian and Nytimes


4. Generate the application key
Generate a unique application key for your Laravel application:
   ```bash
   php artisan key:generate
   ```
5. Set up the database
Make sure you have a MySQL or SQLite database set up, then update the .env file with the correct database credentials:
   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```
Run Migration
   ```bash
   php artisan migrate
   ```
6. Run the application
Now, you can start the Laravel development server:
   ```bash
   php artisan serve
   ```
By default, the application will be available at http://127.0.0.1:8000.



# Running News Aggregator with Docker
## Prerequisites

- Docker
- Docker Compose

## Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/my1companion/news-aggregator-api.git
   cd news-aggregator-api
   ```

2. Update .env for Docker

Ensure your .env file is updated for Dockerized services:

   ```bash
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=laravel
   DB_PASSWORD=laravel
   ```
   ```bash
   MAIL_MAILER=smtp
   MAIL_SCHEME=null
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_ENCRYPTION=tls
   MAIL_USERNAME=1339d66524b3f7
   MAIL_PASSWORD=559ccd495e2b66
   MAIL_FROM_ADDRESS="hello@newsaggregator.com"
   MAIL_FROM_NAME="${APP_NAME}"

   NEWSAPI_API_KEY="YOUR_NEWSAPI_API_KEY"
   GUARDIAN_API_KEY="YOUR_GUARDIAN_API_KEY"
   NYTIMES_API_KEY="YOUR_NYTIMES_API_KEY"

   L5_SWAGGER_GENERATE_ALWAYS=true
   L5_SWAGGER_API_VERSION=1.0.0
   L5_SWAGGER_BASE_PATH=/api
   L5_SWAGGER_TITLE="Your API Documentation"
   ```
Signup to get API KEY on NewsAPI, Gaurdian and Nytimes


3. Build and start the Docker containers:

	```bash
	docker-compose up --build
	```
4. Install Laravel dependencies:
	```bash
	docker exec -it laravel_app composer install
	```
5. Generate the application key:
	```bash
	docker exec -it laravel_app php artisan key:generate
 	```
6. Run database migrations:
	```bash
	docker exec -it laravel_app php artisan migrate
 	```
7. Access the application at http://localhost:8000.

	App: http://localhost:8000
	API Documentation: http://localhost:8000/api/documentation
	Remote API Documentation: https://app.swaggerhub.com/apis-docs/MY1COMPANION/news-aggregator_api/1.0.0
	

Common Commands

Stop containers:
   ```bash
   docker-compose down
   ```

Access the application container:
 
   ```bash
   docker exec -it laravel_app bash
   ```

View logs:
   ```bash
   docker-compose logs -f
   ```

## Testing

5. Set up test database
Make sure you have a MySQL or SQLite database set up, then update the .env.testing file with the correct database credentials:
   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_test_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

2. Run Test Migration:
	```bash
	php artisan migrate --env=testing
 	```

2. Run Test:
	```bash
	php artisan migrate
 	```

