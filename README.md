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
5) visit https://herbarium2.natur.cuni.cz/admin, accept self-signed certificate
6) use link [initialize](https://herbarium2.natur.cuni.cz/admin/home/initialize) to create buckets and copy test files
7) use link [proceed](https://herbarium2.natur.cuni.cz/admin/home/proceed) to run pipeline of image processing
6) test this [sample call](https://herbarium2.natur.cuni.cz/iiif/2/prc_407087.jp2/full/300,/0/default.jpg)

## run in production
follow previous section +
1) make sure ports 8182, 9000 and 9001 are closed for public (or disable in docker-compose.yml file)
2) run Docker containers ```docker compose up -d --build```
3) if works well with self-signed certificates, change STAGE: 'local' to 'production'
4) rerun Docker containers ```docker compose up -d```
5) sample call https://herbarium2.natur.cuni.cz/iiif/2/prc_475444.jp2/full/300,/0/default.jpg


## TODO

### curator
* store tif+jp2 file size
* fix barcodeStage - work with imagick copy (not to save thresholded jp2)
* delete new/xx.tif later, after register stage
* store timestamp_imported
* modify regex to accept "_a" suffix style
* pipeline for iiif/archive bucket postcontrol
* plot tiff filesize, jp2 filesize
* route for specimen id - provide lis of images
* route proxy tif image from S3
*
