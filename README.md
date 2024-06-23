# jacq-PRC
This is a preliminary solution for PRC herbarium interaction with JACQ build from:
* proxy limiting access to public Cantaloupe interface + automatic management of certificates
* Cantaloupe v5 image server with S3 data storage
* MinIO as a demo storage
* web application Curator handles primary data management after uploaded from herbaria
* tbd - replica

## run locally
1) setup docker credentials ```cp sample.env .env ```
2) prepare curator
    ```shell
    cd containers/curator
    ./composer.sh
    chmod -R 777 htdocs/log htdocs/temp
    cp htdocs/config/local.neon.template htdocs/config/local.neon
    ```
3) run Docker containers  ```docker compose up --build```
4) edit /etc/hosts ```127.0.0.1	herbarium2.natur.cuni.cz```
5) create db schema: go inside the container ```docker exec -it --user dfx jacq-curator /bin/bash``` and run ```NETTE_DEBUG=1 bin/console migrations:migrate --no-interaction```
6) visit https://herbarium2.natur.cuni.cz/admin, accept self-signed certificate
7) use first link [initialize](https://herbarium2.natur.cuni.cz/admin/home/initialize) to create buckets and copy test files
8) use second link [proceed](https://herbarium2.natur.cuni.cz/admin/home/proceed) to run pipeline of image processing
9) test sample calls mentioned on the homepage

## run in production
follow wisely previous section +
1) make sure ports 8182, 9000 and 9001 are closed for public (or disable in docker-compose.yml file)
2) mount db storage to host in docker-compose.yml
3) run Docker containers ```docker compose up -d --build```
4) if works well with self-signed certificates, change STAGE: 'local' to 'production'
5) rerun Docker containers ```docker compose up -d```

## TODO

### curator
* transform into real webapp with authorization
* try to get curator on the GitHub Registry - separate curator and deployment
* write a report on GDrive
*
