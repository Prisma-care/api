<?php
$excluded_folders = [
    'node_modules',
    'storage',
    'vendor',
    'migrations',
    'bootstrap',
];
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude($excluded_folders)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@PSR2' => true,
        'binary_operator_spaces' => ['align_double_arrow' => true],
        'array_syntax' => ['syntax' => 'short'],
        'linebreak_after_opening_tag' => true,
        'not_operator_with_successor_space' => true,
        'phpdoc_order' => true,
        'ordered_imports' => ['sortAlgorithm' => 'alpha'],
        'no_unused_imports' => true,
    ))
    ->setFinder($finder)
;