<?php
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'strict_comparison' => true,
        'not_operator_with_successor_space' => false,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->exclude('node_modules')
            ->exclude('storage')
            ->exclude('bootstrap/cache')
            ->notName('_ide_helper.php')
            ->in(__DIR__)
            ->name('*.php')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    )
;
