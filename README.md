# jacq-PRC
This is a preliminary solution for PRC herbarium interaction with JACQ build from:
* proxy limiting access to public Cantaloupe interface + automatic management of certificates
* Cantaloupe v5 image server with S3 data storage
* MinIO as a demo storage with images prc_475443.jp2, prc_475443_a.jp2 and prc_475444.jp2
* web application Curator handles primary data management after uploaded from herbaria
* tbd - replica

## run locally
1) setup docker credentials ```cp sample.env .env ```
2) prepare curator
    ```shell
    cd containers/curator
    ./composer.sh
    chmod -R 777 htdocs/log htdocs/temp
    cp htdocs/config/local.neon.dist htdocs/config/local.neon
    ```
3) run Docker containers  ```docker compose up --build```
4) login at http://localhost:9000 with credentials
5) create bucket named "prc" and upload data/cantaloupe/* into it
6) sample call http://localhost:8182/iiif/2/prc_475444.jp2/full/300,/0/default.jpg

## run in production
follow previous section +
1) make sure ports 8182, 9000 and 9001 are closed for public (or disable in docker-compose.yml file)
2) run Docker containers ```docker compose up -d --build```
3) if works with self-signed certificates, change STAGE: 'local' to 'production'
4) rerun Docker containers ```docker compose up -d```
5) sample call https://herbarium2.natur.cuni.cz/iiif/2/prc_475444.jp2/full/300,/0/default.jpg
