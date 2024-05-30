# jacq-server
deployment for herbarium2.natur.cuni.cz server including MariaDB replica and IIIF server

## MariaDB = replica of JACQ database_input
* https://hub.docker.com/r/yidigun/mariadb-replication - vypadá nejlépe, podle něj postupováno

* https://github.com/gadiener/docker-mariadb-replication/tree/master
* https://mariadb.org/mariadb-replication-using-containers/
* https://blog.devgenius.io/automated-mariadb-replication-using-docker-a585defcc047

## Cantaloupe = IIIF compliant server
https://iiif.io/api/image/3.0/#21-image-request-uri-syntax

has UI, should be disabled/proxy (:8182/admin vs :8182/iiif)- https://training.iiif.io/intro-to-iiif/INSTALLING_CANTALOUPE.html
for every image a info.json is downloaded https://training.iiif.io/intro-to-iiif/SOFTWARE.html, it contains dimension - so why to store it..?

https://cantaloupe-project.github.io/manual/5.0/getting-started.html

## Mirador - IIIF viewer
https://iiif.bgbm.org/
https://services.jacq.org/jacq-services/rest/iiif/manifest/1681482
https://presentation-validator.iiif.io/
https://iiif.io/api/presentation/3.0/

http://iiif.bodleian.ox.ac.uk/manifest-editor/ - online editor for manifest (form https://training.iiif.io/intro-to-iiif/IIIF_MANIFESTS.html)


udělám malou nette app
/iiif/$id - vrací Mirador, odkazuje vlastně jen na manifest
/iiif/manifest/$id - sestaví v3 manifest, měl by být cachovaný
/iiif/embeddable/$id - vrací Mirador ve fullscreenu aby šel dobře do iframe

do budoucna tam může sedět i nějaká infopage k českým herbářům, vlastní vyhledávač jen nad PRC atp.
a používat v3 https://iiif.io/api/presentation/3.0/change-log/
```php
    $latteParams = [
        'variable' => 'Hello, this is a variable from Latte!'
    ];

spíše lépe mát uložený proformu jako .json, tu načíst s cahce a jen modifikovat dílčí aspekt
    // Vytvoření Latte šablony
    $latte = new Nette\Bridges\ApplicationLatte\LatteFactory;
    $template = $latte->create()->renderToString(__DIR__ . '/templates/Api/default.latte', $latteParams);
    $data = JSON:decode($template);

    $this->sendResponse(new JsonResponse($data));
```

