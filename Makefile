intro:
	@echo "   _____                  __                     _____      _               ";
	@echo "  / ____|                / _|                   / ____|    | |              ";
	@echo " | (___  _   _ _ __ ___ | |_ ___  _ __  _   _  | (___   ___| |_ _   _ _ __  ";
	@echo "  \___ \| | | | '_ \` _ \|  _/ _ \| '_ \| | | |  \___ \ / _ \ __| | | | '_ \ ";
	@echo "  ____) | |_| | | | | | | || (_) | | | | |_| |  ____) |  __/ |_| |_| | |_) |";
	@echo " |_____/ \__, |_| |_| |_|_| \___/|_| |_|\__, | |_____/ \___|\__|\__,_| .__/ ";
	@echo "          __/ |                          __/ |                       | |    ";
	@echo "         |___/                          |___/                        |_|    ";

init: intro do-build-containers do-start-containers

start: intro do-start-containers

stop: intro do-stop-containers

down: intro do-clean-docker

do-start-containers:
	@echo "\n=== Starting Containers ===\n"
	@docker compose start

do-stop-containers:
	@echo "\n=== Stopping Containers ===\n"
	@docker compose stop

do-build-containers:
	@echo "\n=== Building Containers ===\n"
	@docker compose build ecs

do-clean-docker:
	@echo "\n=== Cleaning Docker Env ===\n"
	@docker compose down -v