# jacq-PRC
This is a preliminary solution for PRC herbarium interaction with JACQ build from:
* proxy limiting access to public Cantaloupe interface + automatic management of certificates
* Cantaloupe v5 image server with S3 data storage
* MinIO as a demo storage with images prc_475443.jp2, prc_475443_a.jp2 and prc_475444.jp2
* tbd - replica

## run locally
1) setup credentials ```cp sample.env .env ```
2) run Docker containers  ```docker compose up --build```
3) login at http://localhost:9000 with credentials
4) create bucket named "prc" and upload data/cantaloupe/* into it
5) sample call http://localhost:8182/iiif/2/prc_475444.jp2/full/300,/0/default.jpg

## run in production
follow previous section +
1) make sure ports 8182, 9000 and 9001 are closed for public (or disable in docker-compose.yml file)
2) check domain name used in proxy
3) change STAGE: 'local' to 'production'
4) run Docker containers ```docker compose up -d --build```
5) sample call https://herbarium2.natur.cuni.cz/iiif/2/prc_475444.jp2/full/300,/0/default.jpg
