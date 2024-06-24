# jacq-PRC
This is a preliminary solution for PRC herbarium interaction with JACQ build from:
* proxy limiting access to public Cantaloupe interface + automatic management of certificates
* Cantaloupe v5 image server with S3 data storage
* web application Curator handles primary data management after uploaded from herbaria
* replica of JACQ database

## first run locally
1) setup credentials (copy templates and update values) ```cp sample.env .env ``` and ```cp sample.local.neon local.neon ```
2) run Docker containers  ```docker compose up``
3) edit /etc/hosts
```
127.0.0.1	herbarium2.natur.cuni.cz
127.0.0.1	herbarium-iiif.natur.cuni.cz
```

1) create db schema: go inside the container ```docker exec -it --user dfx jacq-curator /bin/bash``` and run ```NETTE_DEBUG=1 bin/console migrations:migrate --no-interaction```
2) visit https://herbarium2.natur.cuni.cz/admin, accept self-signed certificate
3) test sample calls mentioned on the page

## first run in production
follow wisely previous section +
1) make sure ports 8182, 9000 and 9001 are closed for public (or disable in docker-compose.yml file)
3) run Docker containers ```docker compose up -d```
4) if works well with self-signed certificates, change STAGE: 'local' to 'production'
5) rerun Docker containers

## TODO
* replica
* watchtower (?)
