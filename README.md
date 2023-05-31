# 🚀 Maquequette - Back

**Maquequette** est une plateforme d'apprentissage de développement front-end et de webdesign.

## 🧑🏽‍💻 `Developpement Context`

### _Team :_

- Lyes Souifi
- Anguinea Mattheux


## 🏛️ `Technology used`

- Symfony
- API Platform
- Lexik/JWT-Authentication
- Gesdinet/refresh-token

## 🏗️ `Getting started`

### install all dependencies

```sh
composer install
```
### Replace all .env variables

### Create Database with data fixtures

```sh
php bin/console d:d:c

php bin/console d:m:m

php bin/console d:f:l
```

### Launch server

```sh
symfony server:start
```

### Clear cache

```sh
php bin/console cache:clear
```