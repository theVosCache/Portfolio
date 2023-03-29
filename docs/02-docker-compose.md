# Setting up Docker Compose

For a PHP Container:

```yml
  php:
    build:
      context: ./components/module-auth
      dockerfile: ../../docker/Dockerfile-php
    image: module-auth
    user: "1000:1000"
    volumes:
      - "./components/<NAME>:/app"
      - "./docker/php.ini:/usr/local/etc/php/conf.d/custom.ini"
```

For a Nginx Container:

```yml
  web:
    image: nginx
    volumes:
      - "./components/module-auth:/app"
      - "./docker/nginx.conf:/etc/nginx/conf.d/default.conf"
      - "./docker/php.ini:/usr/local/etc/php/conf.d/custom.ini"
    depends_on:
      - php
    ports:
      - "8000:80"
```