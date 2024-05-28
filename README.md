# jacq-server
deployment for herbarium2.natur.cuni.cz server including MariaDB replica and IIIF server

## MariaDB = replica of JACQ database_input
* https://hub.docker.com/r/yidigun/mariadb-replication - vypadá nejlépe, podle něj postupováno

* https://github.com/gadiener/docker-mariadb-replication/tree/master
* https://mariadb.org/mariadb-replication-using-containers/
* https://blog.devgenius.io/automated-mariadb-replication-using-docker-a585defcc047

## Cantaloupe = IIIF compliant server
https://iiif.io/api/image/3.0/#21-image-request-uri-syntax
