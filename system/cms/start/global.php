<?php

use Composer\Autoload\ClassLoader;

/**
 * This document tells a sad story of doing gross things to make CodeIgniter 
 * flexible enough for PyroCMS to do what it needs to do. 
 * 
 * Everything in here is deprecated and will hopefully die during or after 3.x.
 */


App::bind('ClassLoader', function ($app) {

    $loader = new ClassLoader;

    // Map the streams model namespace to the site ref
    $siteRef = str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', SITE_REF)));

    // Register module manager for usage everywhere, its handy
    $loader->add('Pyro\\Module\\Settings', $app['path'] . '/modules/settings/src/');
    $loader->add('Pyro\\Module\\Addons', $app['path'] . '/modules/addons/src/');
    $loader->add('Pyro\\Module\\Streams', $app['path'] . '/modules/streams_core/src/');
    $loader->add('Pyro\\Module\\Users', $app['path'] . '/modules/users/src/');

    $loader->add(
        'Pyro\\Module\\Streams\\Model',
        $app['path'] . '/modules/streams_core/models/' . $siteRef . 'Site/'
    );

    // activate the autoloader
    $loader->register();

    return $loader;
});

App::bind('Markdown', function () {
    return new Parsedown;
});
