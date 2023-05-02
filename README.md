# 🦓 Zebrina Frontend

Start Database and Services

```
# Start services
$> docker-compose up

# Start asset watcher
$> npm run watch

# Run Server
$> symfony serve
```

## Services

* Postgres
* pgadmin: [localhost:5050](localhost:5050)

## Installation

```
$> docker-compose up --build

# Install dependencies
$> symfony composer install

# Install frontend depdendencies
$> npm install
$> npm run build

# After setting up your database, be sure to execute the following database query
# "CREATE EXTENSION postgis;"

# Import dump into the db container
$> pv dump.pg | docker exec -i zebrina_database_1 psql -U zebrina_user zebrina_db
```

# Test

Run test suites

```
# Run all tests
$> symfony composer test

# Generate HTML Coverage report
$> symfony composer phpunit-generate-coverage
```

# Generate License List

```
$> symfony composer licenses
$> npx license-checker --summary
```
