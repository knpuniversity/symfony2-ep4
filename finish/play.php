<?php

use Symfony\Component\HttpFoundation\Request;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
umask(0000);

$loader = require_once __DIR__.'/app/bootstrap.php.cache';
require_once __DIR__.'/app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$kernel->boot();

$container = $kernel->getContainer();
$container->enterScope('request');
$container->set('request', $request);

// all our setup is done!!!!!!


$em = $container->get('doctrine')
    ->getEntityManager()
;

/** @var $user \Yoda\UserBundle\Entity\User */
$user = $em
    ->getRepository('UserBundle:User')
    ->findOneBy(array('username' => 'user'))
;

/** @var $event \Yoda\EventBundle\Entity\Event */
$event = $em
    ->getRepository('EventBundle:Event')
    ->findOneBy(array('name' => 'Rebellion Fundraiser Bake Sale!'))
;

// this totally works!
$event->setOwner($user);

// does nothing :(
$events = $user->getEvents();
$events[] = $event;
$user->setEvents($events);

$em->persist($user);
$em->persist($event);
$em->flush();

