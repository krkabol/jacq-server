# jacq-server

## TBD
- limit ports
- proxy for cantaloupe
- Let's Encrypt

## description
deployment for herbarium2.natur.cuni.cz server including MariaDB replica and IIIF server

- automatic updates - https://phoenixnap.com/kb/automatic-security-updates-ubuntu
- require pubkey - https://www.linux.org/threads/how-to-force-ssh-login-via-public-key-authentication.8726/
- limit open ports -

## MariaDB = replica of JACQ database_input
* https://hub.docker.com/r/yidigun/mariadb-replication - vypadá nejlépe, podle něj postupováno

* https://github.com/gadiener/docker-mariadb-replication/tree/master
* https://mariadb.org/mariadb-replication-using-containers/
* https://blog.devgenius.io/automated-mariadb-replication-using-docker-a585defcc047

## Cantaloupe = IIIF compliant server
https://iiif.io/api/image/3.0/#21-image-request-uri-syntax
https://training.iiif.io/intro-to-iiif/IIIF_MANIFESTS.html

has UI, should be disabled/proxy (:8182/admin vs :8182/iiif)- https://training.iiif.io/intro-to-iiif/INSTALLING_CANTALOUPE.html
for every image a info.json is downloaded https://training.iiif.io/intro-to-iiif/SOFTWARE.html, it contains dimension - so why to store it..?

https://cantaloupe-project.github.io/manual/5.0/getting-started.html

sample call http://localhost:8182/iiif/2/prc_475444.jp2/full/300,/0/default.jpg

## Minio = demo S3 storage
https://registry.hub.docker.com/r/minio/minio
http://www.sefidian.com/2022/04/08/deploy-standalone-minio-using-docker-compose/

> The MinIO deployment starts using default root credentials minioadmin:minioadmin. You can test the deployment using the MinIO Console, an embedded object browser built into MinIO Server. Point a web browser running on the host machine to http://127.0.0.1:9000 and log in with the root credentials. You can use the Browser to create buckets, upload objects, and browse the contents of the MinIO server.

using prc_475443 and prc_475444 records as tester in bucket "prc".
