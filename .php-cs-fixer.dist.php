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
        'no_unused_imports' => true,
        'no_trailing_whitespace' => true,
        // Disabled: don't match this codebase's existing conventions.
        // Enabling these would mean reformatting ~125 files rather than
        // reflecting how the code actually looks; the copyright header
        // comment is always placed directly after `<?php` with no gap.
        'blank_line_after_opening_tag' => false,
        'statement_indentation' => false,
        'blank_line_between_import_groups' => false,
        'return_type_declaration' => false,
        'no_blank_lines_after_class_opening' => false,
        'single_line_after_imports' => false,
        'full_opening_tag' => false,
    ])
    ->setFinder($finder);
