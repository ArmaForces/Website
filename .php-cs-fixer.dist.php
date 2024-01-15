<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('node_modules')
    ->notPath('config/bundles.php')
    ->notPath('config/bootstrap.php')
    ->notPath('config/preload.php')
    ->notPath('public/index.php')
    ->notPath('src/Kernel.php')
    ->notPath('tests/bootstrap.php')

    # Codeception
    ->exclude('tests/_data')
    ->exclude('tests/_output')
    ->exclude('tests/_support/_generated')
;

return (new PhpCsFixer\Config())
    ->setLineEnding("\n") // Linux LF line ending
    ->setRiskyAllowed(true)
    ->setRules([
        // Use risky rules for new projects only
        // Uncomment @PHPx rules up to target PHP version
        '@DoctrineAnnotation' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PHP80Migration' => true,
        '@PHP80Migration:risky' => true,

        'php_unit_internal_class' => false,
        'php_unit_method_casing' => false,
        'php_unit_test_class_requires_covers' => false,
        'phpdoc_separation' => false,
        'phpdoc_to_comment' => false,
        'ordered_class_elements' => [
            'order' => [
                'use_trait', // traits

                'constant_public', // constants
                'constant_protected',
                'constant_private',

                'property_public_static', // static properties
                'property_protected_static',
                'property_private_static',

                'property_public', // properties
                'property_protected',
                'property_private',

                'construct', // magic methods
                'destruct',
                'magic',

                'phpunit', // PHPUnit

                'method_public_static', // static methods
                'method_protected_static',
                'method_private_static',

                'method_public', // methods
                'method_protected',
                'method_private',
            ],
            'sort_algorithm' => 'none',
        ],
    ])
    ->setFinder($finder)
;
