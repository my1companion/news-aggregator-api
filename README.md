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
