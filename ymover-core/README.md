# YMover Core

## Setup

1.  Run `composer install` to install dependencies.
2.  Copy `.env.example` to `.env` and configure your database credentials.
3.  Run migrations: `vendor/bin/phinx migrate`.
4.  Serve the application: `php -S localhost:8000 -t public`.

## Structure

-   `app/`: Core application code.
-   `db/`: Database migrations.
-   `public/`: Web entry point.
-   `views/`: HTML templates.

## Architecture

-   **Router**: Bramus Router (via `App\Core\Router`).
-   **Database**: PDO Singleton (`App\Core\Database`).
-   **Models**: Simple DAO/Active Record pattern extending `App\Models\BaseModel`.
-   **Views**: Native PHP templates with `App\Core\View` renderer.
