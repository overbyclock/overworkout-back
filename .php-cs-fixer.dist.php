<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude(['var', 'vendor', 'config/secrets'])
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->files()
    ->name('*.php')
;

return (new Config())
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'trailing_comma_in_multiline' => true,
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_trim' => true,
        'phpdoc_separation' => true,
        'phpdoc_align' => true,
        'single_quote' => true,
        'no_extra_blank_lines' => true,
        'no_whitespace_in_blank_line' => true,
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'class_attributes_separation' => [
            'elements' => ['method' => 'one', 'property' => 'one'],
        ],
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'modernize_types_casting' => true,
        'native_function_invocation' => [
            'include' => ['@compiler_optimized'],
            'scope' => 'namespaced',
        ],
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
;
