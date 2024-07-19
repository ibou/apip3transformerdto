## Name

KIRANICO API

## Description

This project is an API that provides information about the game "Monster Hunter Rise" and its DLC, Sunbreak.

## Context

This is a personal project developed in my spare time. It has allowed me to further enhance my knowledge of API Platform and PHP/Symfony development in general. Several endpoints are available at /api.

## Installation

To install the project, simply clone the repository and ensure you have `make` installed. Once done, run the following command at the root of the project:

```sh
make dc-install
```

This will:

1. Start the Docker containers
2. Install the PHP dependencies
3. Create the database

## Technologies

- PHP 8.3
- Symfony 7.0
- API Platform 3.3
- PHPUnit 10

## Usage

To use the API, you can either rely on the fixtures or use the actual data. The fixtures can be loaded with the following command:

```sh
make db-fixtures-append
```

You can also use the following command to reset the database and load the fixtures:

```sh
make db-reset-f
```

To use the real data from the Kiranico site, you have two options.

First option, you can synchronize the data with the command:

```sh
make sync
```

This command will synchronize the data and populate your database with all the data from the Kiranico site at that point in time.

Second option, import an SQL dump. You will find the various synchronizations I have performed in the form of SQL dumps in the /dump folder. You can then import this database like any other PostgreSQL dump.

## License

GNU GENERAL PUBLIC LICENSE Version 3

## Project Status

The project is complete. It is functional and can be used as is. There are several parts left to implement (Endemic Life, etc.). These may be implemented in the future.
