# Copy Feed Command - Laravel CLI Tool

## Introduction

This Laravel CLI tool is designed to copy entries from the `feeds` table, along with related data from the `instagram_sources`, `tiktok_sources`, and `posts` tables. This tool addresses the challenge of investigating bugs in production data by allowing selective copying of necessary data without transferring entire databases.

## Features

- Copy a feed entry by ID.
- Optionally copy related Instagram or TikTok sources.
- Optionally include a specified number of related posts.

## Prerequisites

- PHP 7.4 or higher
- Laravel 8.x or higher
- MySQL

## Installation

1. Clone the repository:

    ```sh
    git clone https://github.com/t0nn1x/storyclash-project
    cd storyclash-project
    ```

2. Install dependencies:

    ```sh
    composer install
    ```

3. Set up environment variables:

    ```sh
    cp .env.example .env
    ```

4. Configure the `.env` file with your database settings:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_SOURCE_DATABASE=source_db
    DB_DESTINATION_DATABASE=destination_db
    DB_USERNAME=root
    DB_PASSWORD=password
    ```

5. Run migrations and seed the database:

    ```sh
    php artisan migrate
    php artisan db:seed --database=source
    ```

## Usage

### Basic Usage

To copy a feed entry with ID 123:

```sh
php artisan copy 123
```

This command will copy the feed with ID 123 and all related Instagram and TikTok sources.

### Copy Only Instagram Sources

To copy a feed entry with ID 123 and only related Instagram sources:

```sh
php artisan copy 123 --only=instagram
```

### Copy Only TikTok Sources

To copy a feed entry with ID 123 and only related TikTok sources:

```sh
php artisan copy 123 --only=tiktok
```

### Include Related Posts

To copy a feed entry with ID 123 and include 5 related posts:

```sh
php artisan copy 123 --include-posts=5
```

### Full Command Example

To copy a feed entry with ID 123, only Instagram sources, and include 5 related posts:

```sh
php artisan copy 123 --only=instagram --include-posts=5
```

## Testing

This project includes unit tests to verify the functionality of the CLI tool. To run the tests, use the following command:

```sh
php artisan test
```

### Mocking Database Operations

The tests use Mockery to mock database operations, ensuring that the tests are isolated from the actual database.

## Example Output

### Successful Copy

```sh
$ php artisan copy 123
Feed copied successfully.
```

### Feed Not Found

```sh
$ php artisan copy 999
Feed not found.
```

### Invalid Source Type

```sh
$ php artisan copy 123 --only=invalid_source
Invalid source type.
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
