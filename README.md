# Laravel Project with Sail, Vite, Vue 3, and Quasar

This is a project built with Laravel, Vite, Vue 3 and Quasar.

## Features

-   **Scraping of liturgies from cancaonova.com**
-   **Modern design using Quasar Framework components (Material Design)**
-   **Light and dark mode themes**
-   **Responsive design that adapts well to all screen sizes**

## Requirements

-   Docker
-   Composer

## Installation

1. Clone the repository:

```bash
git clone git@github.com:ThiagoGalhardo/liturgia-diaria.git
```

2. Navigate to the project directory:

```bash
cd liturgia-diaria
```

3. Copy file .env:

```bash
cp .env.example .env
```

4. Composer install:

```bash
composer install
```

5. Start the Docker containers:

```bash
./vendor/bin/sail up
```

6. Generate the application key:

```bash
./vendor/bin/sail artisan key:generate
```

7. Run the database migrations:

```bash
./vendor/bin/sail artisan migrate
```

8. Compile the front-end assets:

```bash
./vendor/bin/sail npm run dev
```

9. Start laravel server:

```bash
./vendor/bin/sail artisan serve
```
