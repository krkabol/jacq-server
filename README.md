# jacq-server
deployment for herbarium2.natur.cuni.cz server including MariaDB replica and IIIF server

## MariaDB = replica of JACQ database_input
* https://hub.docker.com/r/yidigun/mariadb-replication - vypadá nejlépe, podle něj postupováno

* https://github.com/gadiener/docker-mariadb-replication/tree/master
* https://mariadb.org/mariadb-replication-using-containers/
* https://blog.devgenius.io/automated-mariadb-replication-using-docker-a585defcc047

## Cantaloupe = IIIF compliant server
https://iiif.io/api/image/3.0/#21-image-request-uri-syntax

## Mirador - IIIF viewer
https://iiif.bgbm.org/
https://services.jacq.org/jacq-services/rest/iiif/manifest/1681482
https://presentation-validator.iiif.io/
https://iiif.io/api/presentation/3.0/


udělám malou nette app
/$id - vrací Mirador, odkazuje vlastně jen na manifest
/manifest/$id - sestaví v3 manifest
/embeddable/$id - vrací Mirador ve fullscreenu aby šel dobře do iframe

```php
    $latteParams = [
        'variable' => 'Hello, this is a variable from Latte!'
    ];

    // Vytvoření Latte šablony
    $latte = new Nette\Bridges\ApplicationLatte\LatteFactory;
    $template = $latte->create()->renderToString(__DIR__ . '/templates/Api/default.latte', $latteParams);
    $data = JSON:decode($template);

    $this->sendResponse(new JsonResponse($data));
```

