# News Aggregator Laravel Dockerized Application

## Prerequisites

- Docker
- Docker Compose

## Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/my1companion/news-aggregator-api.git
   cd news-aggregator-api
    ```

Update .env for Docker

Ensure your .env file is updated for Dockerized services:

   ```bash
	DB_CONNECTION=mysql
	DB_HOST=db
	DB_PORT=3306
	DB_DATABASE=laravel
	DB_USERNAME=laravel
	DB_PASSWORD=laravel
	```

2. Build and start the Docker containers:

	```bash
	docker-compose up --build
	```
3. Install Laravel dependencies:
	```bash
	docker exec -it laravel_app composer install
	```
4. Generate the application key:
	```bash
	docker exec -it laravel_app php artisan key:generate
 	```
5. Run database migrations:
	```bash
	docker exec -it laravel_app php artisan migrate
 	```
 6. Access the application at http://localhost:8000.

	App: http://localhost:8000
	API Documentation: http://localhost:8000/api/documentation
	Remote API Documentation: https://app.swaggerhub.com/apis/MY1COMPANION/new-aggregator/1.0.0
	

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

Notes
The database is persisted in the dbdata volume.
Make sure your storage and bootstrap/cache directories are writable.


---

### **7. Run the Project**

1. Build and start the containers:
   ```bash
   docker-compose up --build
   ```
Open your browser and visit: http://localhost:8000.
