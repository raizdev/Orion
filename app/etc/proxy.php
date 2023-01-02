<?php
$alias = 'App';
$proxy = \Orion\Core\Proxy\App::class;
$manager = new Statical\Manager();
$manager->addProxyInstance($alias, $proxy, $app);
