<?php

// config for TLabsCo/ConfigWalker
return [
    /*
     * This enable load all application config to instance ConfigWalker
     */
    'loadWithAppConfig' => false,

    /*
     * This enable auto load to config for those class/ model / enum has using
     * ConfigWalker Trait or ConfigWalker Interface
     * TODO: implement
     */
    'autoload' => false,

    /*
     * This require 'autoload=true'
     * Indicate which is scan and autoload by config your class in configFrom
     * TODO: implement
     */
    'configFrom' => [
        'models' => '*',
        'enums' => '*',
        'custom' => [], // define list of class that inherit ConfigWalkerAware Interface
    ],
];
