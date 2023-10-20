# focus

## Container structures

```bash
├── focus_web
└── focus_app
└── focus_db
```
### web container

- Base image
  - [nginx](https://hub.docker.com/_/nginx):1.24-alpine

### app container

- Base image
  - [php](https://hub.docker.com/_/php):php:8.2.8-fpm-bullseye
  - [node](https://hub.docker.com/_/node):18.16-bullseye-slim
  - [composer](https://hub.docker.com/_/composer):2.4.4

### db container

- Base image
  - [mysql](https://hub.docker.com/_/mysql):8.0.34-debian
