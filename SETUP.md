# Laravel Docker Setup

## Prerequisites
- Docker Desktop installed
- Git installed

## Quick Start

1. **Clone the project**
   ```bash
   git clone <repository-url>
   cd project-name

2.Copy environment file

bash
cp .env.docker .env

3.Generate application key

bash
docker-compose run --rm composer php artisan key:generate

4.Start the application

bash
docker-compose up -d

5.Run migrations (optional)

bash
docker-compose exec app php artisan migrate

6.Access the application

Open browser: http://localhost:8000
