# Symfony Project

## Project Setup

### Prerequisites

- Docker
- Docker Compose
- Make
- Symfony CLI

### Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/symfony_project.git
    cd symfony_project
    ```

2. Start Docker containers:

    ```bash
    make up
    ```

3. Install PHP dependencies using Composer:

    ```bash
    docker-compose exec php composer install
    ```

4. Install JavaScript dependencies using npm:

    ```bash
    docker-compose exec node npm install
    ```

5. Run database migrations:

    ```bash
    make migrate
    ```

6. Load database fixtures:

    ```bash
    make seed
    ```

### Running the Project

1. Start the Docker containers (if not already started):

    ```bash
    make up
    ```

2. Build CSS assets:

    ```bash
    npm run build:css
    ```

3. Start the Symfony server:

    ```bash
    symfony server:start
    ```

4. Access the application in your browser at `http://localhost`.

### Running Async Jobs

To run async jobs using Symfony Messenger:

```bash
make run-job
```

### Stopping the Project

To stop the Docker containers and remove volumes:

```bash
make down
```

### Available Make Commands

- `make up`: Start Docker containers
- `make down`: Stop Docker containers and remove volumes
- `make migrate`: Run database migrations
- `make seed`: Load database fixtures
- `make run-job`: Run async jobs
- `make help`: Display available commands

### License

This project is licensed under the MIT License.
