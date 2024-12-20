# Nom du projet
PROJECT_NAME = symfony_project

# Commandes Docker
UP = docker compose up -d
DOWN = docker compose down -v

# Commandes Symfony/Doctrine
MIGRATE = php bin/console doctrine:migrations:migrate
SEED = php bin/console doctrine:fixtures:load
RUN_JOB = symfony console messenger:consume async -vv

# Règles Makefile
.PHONY: up down migrate seed run-job help

# Démarre les conteneurs Docker
up:
	@echo "🚀 Starting Docker containers..."
	@$(UP)

# Arrête les conteneurs Docker et supprime les volumes
down:
	@echo "🛑 Stopping Docker containers and removing volumes..."
	@$(DOWN)

# Exécute les migrations
migrate:
	@echo "📦 Running database migrations..."
	@$(MIGRATE)

# Charge les fixtures dans la base de données
seed:
	@echo "🌱 Loading database fixtures..."
	@$(SEED)

# Exécute les travaux Messenger
run-job:
	@echo "📡 Running async jobs with Messenger..."
	@$(RUN_JOB)

# Affiche l'aide pour les commandes disponibles
help:
	@echo "🛠️ Available Makefile commands:"
	@echo "  make up         - Start Docker containers"
	@echo "  make down       - Stop Docker containers and remove volumes"
	@echo "  make migrate    - Run database migrations"
	@echo "  make seed       - Load database fixtures"
	@echo "  make run-job    - Run async jobs using Symfony Messenger"
	@echo "  make help       - Show this help message"
