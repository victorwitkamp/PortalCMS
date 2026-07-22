<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/portal')
    ->in(__DIR__ . '/config')
    ->name('*.php')
    ->exclude('dist');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'trailing_comma_in_multiline' => true,
        'no_trailing_whitespace' => true,
        'single_quote' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
