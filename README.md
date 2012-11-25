# Doctrine Fun: Bring fun(ctional APIs) into Doctrine by integrating PhpOption with Doctrine [![Build Status](https://secure.travis-ci.org/lstrojny/doctrine-fun.png?branch=master)](https://travis-ci.org/lstrojny/doctrine-fun)

Extends Doctrine to integrate [PhpOption](https://github.com/schmittjoh/php-option). Comes with a custom repository class that returns `PhpOption\LazyOption` instead of null or entity.


#### Use functional repository
```php
<?php
$repository = $em->getRepository('MyEntity');

// EntityRepository::find() returns instance of \PhpOption\Option instead of plain entity
$entity = $repository->find(12)->getOrElse(new Entity());

// EntityRepository::findOneBy(...) returns Option as well
$entity = $repository->findOneBy(array('property' => 'value'))->get();
```

#### Set functional repository as a default repository class
```php
<?php
$config = new Doctrine\ORM\Configuration();
$config->setDefaultRepositoryClassName('Doctrine\Fun\EntityRepository');

$em = EntityManager::create(..., $config);
```
