# Battleship Game API

## project purpose

* starting a symfony 5 project
* demonstrate technical knowledge

## software requirements

* [PHP >=7.4](https://www.php.net/downloads)
* [composer >=1.10](https://getcomposer.org/)
* [Symfony CLI-Tool](https://symfony.com/download)

## technology stack

* PHP 7.1
 * [Symfony Framework 5](http://symfony.com)
 * [Doctrine 2](http://doctrine-orm.readthedocs.io/en/latest)
 * [Composer](https://getcomposer.org)
 * [Twig](http://twig.sensiolabs.org)
 * [PHPUnit](https://phpunit.de)
 * [json schema](http://json-schema.org/)
 
 ## how to install
 
 ```
 $ git clone https://github.com/rabbl/battleship-api.git
 $ cd battleship-api
 $ composer install
 $ bin/console doctrine:database:create
 $ bin/console doctrine:schema:create 
```

## how to run locally

```
$ symfony server:start
```

Now you can run [localhost:8000](http://localhost:8000) in your browser.

## how to play

### Implemented opponent strategies

You can choose between three strategies:

* Full random (1)
* Random with unique shots only (2)
* [Hunt/Target strategy](http://www.datagenetics.com/blog/december32011/) (3)

### Via API

You can play over the API.
In the [docs-folder](docs) you can find a [Postman-Project](https://www.postman.com/).

With [Httpie](https://httpie.org/) installed you can do:

```console
<!--- Delete existing game -->
$ http DELETE localhost:8000/dce6bc9f-9c5d-4e99-8795-f7db8017a8da
  HTTP/1.1 201 Created
  Cache-Control: no-cache, private
  Content-Length: 1
  Content-Type: text/html; charset=UTF-8
  Date: Sun, 30 Aug 2020 09:55:58 GMT
  X-Powered-By: PHP/7.4.8
  X-Robots-Tag: noindex

<!-- Creating a new game with automatic ship placement and Hunt/Target strategy -->
$ http --json POST localhost:8000 id=dce6bc9f-9c5d-4e99-8795-f7db8017a8da name='Player 1' strategy:=3
HTTP/1.1 201 Created
  Cache-Control: no-cache, private
  Content-Length: 1
  Content-Type: text/html; charset=UTF-8
  Date: Sun, 30 Aug 2020 09:56:05 GMT
  X-Powered-By: PHP/7.4.8
  X-Robots-Tag: noindex

<!-- Make a shot -->
$ http --json POST localhost:8000/dce6bc9f-9c5d-4e99-8795-f7db8017a8da/shot letter='A' number:=1
  HTTP/1.1 201 Created
  Cache-Control: no-cache, private
  Content-Length: 1
  Content-Type: text/html; charset=UTF-8
  Date: Sun, 30 Aug 2020 09:57:04 GMT
  X-Powered-By: PHP/7.4.8
  X-Robots-Tag: noindex

<!-- Retrieve result of the shot ->
$ http --json GET localhost:8000/dce6bc9f-9c5d-4e99-8795-f7db8017a8da/shot/A/1
  HTTP/1.1 200 OK
  Cache-Control: no-cache, private
  Content-Length: 47
  Content-Type: application/json
  Date: Sun, 30 Aug 2020 10:00:11 GMT
  X-Powered-By: PHP/7.4.8
  X-Robots-Tag: noindex
  
  {
      "letter": "A",
      "number": 1,
      "result": 0,
      "shipId": 0
  }

<!-- Retrieve game status ->
$ http --json GET localhost:8000/dce6bc9f-9c5d-4e99-8795-f7db8017a8da
  HTTP/1.1 200 OK
  Cache-Control: no-cache, private
  Content-Length: 1553
  Content-Type: application/json
  Date: Sun, 30 Aug 2020 10:01:53 GMT
  X-Powered-By: PHP/7.4.8
  X-Robots-Tag: noindex
  
  {
    "id": "dce6bc9f-9c5d-4e99-8795-f7db8017a8da",  
    "human": {
          "name": "Player 1",
          "placedShips": [
              {"hole": ["C",4],"id": 1,"orientation": 0},
              ...
          ],
          "placedShots": [
              {"hole": ["A",1]}
              ...  
          ],
          "strategyId": 3
      },
      "oceanView": [
          [[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0]],
          ...
      ],
      "score": {
          "computer": 0,
          "human": 0,
          "message": ""
      },
      "targetView": [
          [[0,0], null, null, null, null, null, null, null, null, null],
          ...
  }

```


### In your browser

You can play also in your browser.
Starting the game setting your name and choosing a strategy starts a new game and redirects to it.
Clicking in the fields in ocean view you set your shots.
The computer plays your opponent and shots following the strategy rules.

## how run the tests

```
$ bin/phpunit
```
