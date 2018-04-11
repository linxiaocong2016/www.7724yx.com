<?php

$mem = new Memcache;
$mem->connect('localhost', 11211);
$mem->set('name', 'Jason');
var_dump($mem->get('name'));
