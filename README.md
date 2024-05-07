## Name

💀 Symfony skeleton

## Context

This is a personal project that I wanted to put in place to facilitate the start of my future project.

## Description

This project is a Symfony 6.3 / PHP 8.2 skeleton application to start new projects with a suite of tools already installed and configured.

This project contains : 
- Symfony project configuration
- Docker configuration
- Makefile
- Many tools for development

### Symfony project configuration

It contains basic configuration of a symfony project generated with this command.
Webpack Encore has been configured to support SCSS files, images and simple Javascript.

A default controller (src/Controller/DefaultController.php) has been added with a classic Twig file renderer.

### Docker configuration

The docker configuration is based on a docker-compose.yml. This contains 3 services:
- nginx
- symfony
- db

The Symfony service is based on a Dockerfile (/docker/symfony/Dockerfile). This Dockerfile is built from the php:8.1-fpm-buster, to which I added :

- Composer
- Dev tools (Git / Zip)
- NodeJS / Yarn
- PHP extensions

### Makefile

To view all makefile commands :

    make help

### Outils

Several tools have been installed to facilitate development, but also to check code quality.
The Makefile contains a number of useful commands:

    make cc (Empty the cache)
    make test (Run tests)
    make db-update (Update the project / the composer dependencies / the database schema)

It also contains a command that lets you analyze code quality and ensure that conventions are respected:
    make check

This will check :
- Correct code (PHP CS Fixer)
- Code analysis (PHPStan & PHPSlam)
- Validate database schema (php bin/console doctrine:schema:validate)
- Run tests (PHP Unit)

## Installation

With the Makefile, simply launch :

    make dc-install

To do this, you need to install [make](https://doc.ubuntu-fr.org/ubuntu-make).

## Project Status

This project is on hold, awaiting further improvements if necessary. The next step is to upgrade to Symfony 6.4 in November 2023.
