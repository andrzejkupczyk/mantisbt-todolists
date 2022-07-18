<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.9.4|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP54Migration' => true,
        '@PHP70Migration' => true,
        '@PHP70Migration:risky' => true,
        '@PHP56Migration:risky' => true,
        '@PSR1' => true,
        '@PSR12:risky' => true,
        '@PSR12' => true,
        '@PSR2' => true,
        'concat_space' => ['spacing'=>'one'],
        'no_unneeded_curly_braces' => true,
        'no_unused_imports' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('vendor')
        ->in(__DIR__)
    )
    ;
