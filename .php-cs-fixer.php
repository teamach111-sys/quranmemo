<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude(['bootstrap', 'storage', 'vendor', 'node_modules']);

return (new Config)
    ->setRules([
        '@PSR12' => true,
        'single_quote' => true,
        'no_unused_imports' => true,
        'single_blank_line_at_eof' => true,
        'no_trailing_whitespace' => true,
        'cast_spaces' => true,
        'concat_space' => ['spacing' => 'one'],
    ])
    ->setFinder($finder);
