<?php
$header = <<<'EOF'
This file is part of emanci/laravel-referral package.

(c) emanci <zhengchaopu@gmail.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->exclude(['vendor', 'data', 'coverage-html'])
    ->in(__DIR__);
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PSR1' => false,
        'header_comment' => [
            'header' => $header
        ],
        'ordered_class_elements' => true,
        'strict_param' => true,
    ])
    ->setUsingCache(false)
    ->setFinder($finder);
