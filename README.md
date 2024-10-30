# TASK SCHEDULING

## Requirements
- PHP 8.3
- Composer
- PostgreSQL

## Installation

1. Clone the repository:
    ```sh
    git clone git@github.com:SerhanYavcin/task-scheduling.git
    cd task-scheduling
    ```
2. Run `composer install`
3. Run ` docker-compose up -d `
4. Create the database `php bin/console doctrine:database:create`
5. Run the migrations `php bin/console doctrine:migrations:migrate`
6. Run the seeders `php bin/console doctrine:fixtures:load`


## Usage
1. Fetch task list `php bin/console app:task-manager sync`
2. Run `symfony server:start`
3. Go to `http://localhost:8000`