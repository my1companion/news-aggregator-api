# News Aggregator
   The News Aggregator application is designed to fetch and consolidate news articles from multiple reliable sources, providing users with a unified and streamlined news consumption experience. The application retrieves data from the following sources:

## NewsAPI: 
  A powerful service offering a wide range of articles from various publishers and blogs.
## The Guardian: 
  A trusted source for in-depth reporting and analysis on global events, politics, and culture.
## The New York Times (NYTimes): 
  A world-renowned publication delivering high-quality journalism on diverse topics including current affairs, business, and lifestyle.

# How It Works
   Fetch News: The application sends requests to the APIs of NewsAPI, The Guardian, and The New York Times to retrieve the latest articles based on user preferences (e.g., topics, categories, or keywords).

   Aggregate Articles: Articles from these sources are parsed and combined into a single, cohesive feed.

   Deliver Content: The aggregated news feed is presented in a user-friendly interface, ensuring users get the latest updates from multiple sources in one place.

# Key Features
  Multiple Sources: Consolidates news from three major platforms to ensure a diverse and comprehensive view of current events.
  Customizable Feed: Fetch articles based on specific topics, keywords, or categories.
  Real-Time Updates: Regularly syncs with APIs to provide the latest news.

# API Integration
  NewsAPI: https://newsapi.org
  
  The Guardian API: https://open-platform.theguardian.com/
  
  NYTimes API: https://developer.nytimes.com/


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

1. Set up test database
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

3. Run Test:
	```bash
	php artisan migrate
 	```

## Scheduled Commands

   This project includes three scheduled Artisan commands to fetch news from various sources. These commands are set to run hourly via the Laravel scheduler:

   fetch:newsapi - Fetches articles from NewsAPI.
   fetch:guardian - Fetches articles from The Guardian.
   fetch:nytimes - Fetches articles from The New York Times.

## Running the Commands Locally
   To manually execute these commands on your local environment:

   Open your terminal.
   Navigate to the project root directory.
   Run the desired command:
   ```bash
   php artisan fetch:newsapi
   php artisan fetch:guardian
   php artisan fetch:nytimes
   ```
   To test all scheduled commands as they would run in the scheduler, use:
   ```bash
   php artisan schedule:run
   ```
   This simulates the Laravel scheduler and triggers all commands due at the current time.

## Running the Commands in Docker
   If you're running the project in a Docker container, follow these steps:

1. Access the Laravel container shell:   
   ```bash
   docker exec -it laravel_app bash
   ```
2. Run the desired Artisan command inside the container:
   ```bash
   php artisan fetch:newsapi
   php artisan fetch:guardian
   php artisan fetch:nytimes
   ```
   Alternatively, if you want to execute the commands directly without entering the container shell, use:
   ```bash
   docker exec -it laravel_app php artisan fetch:newsapi
   docker exec -it laravel_app php artisan fetch:guardian
   docker exec -it laravel_app php artisan fetch:nytimes
   ```
   To simulate the scheduler in Docker:
   ```bash
   docker exec -it laravel_app php artisan schedule:run
   ```
