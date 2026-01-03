<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
	->files()
	->in([__DIR__ . '/application'])
	->append([__DIR__ . '/index.php'])
	->exclude(['assets', 'cache', 'logs', 'system', 'vendor'])
	->name('*.php')
	->ignoreDotFiles(true)
	->ignoreVCS(true);

return (new Config())
	->setRiskyAllowed(false)
	->setIndent("\t")
	->setLineEnding("\n")
	->setRules([
		'encoding' => true,
		'full_opening_tag' => true,
		'line_ending' => true,
		'indentation_type' => true,
		'array_indentation' => true,
		'binary_operator_spaces' => true,
		'cast_spaces' => ['space' => 'single'],
		'concat_space' => ['spacing' => 'one'],
		'elseif' => true,
		'function_typehint_space' => true,
		'include' => true,
		'lowercase_keywords' => true,
		'no_closing_tag' => true,
		'no_empty_statement' => true,
		'no_extra_blank_lines' => true,
		'no_leading_import_slash' => true,
		'no_mixed_echo_print' => ['use' => 'echo'],
		'no_multiline_whitespace_around_double_arrow' => true,
		'no_short_bool_cast' => true,
		'no_singleline_whitespace_before_semicolons' => true,
		'no_spaces_around_offset' => true,
		'no_trailing_whitespace' => true,
		'no_trailing_whitespace_in_comment' => true,
		'no_whitespace_before_comma_in_array' => true,
		'no_whitespace_in_blank_line' => true,
		'normalize_index_brace' => true,
		'object_operator_without_whitespace' => true,
		'phpdoc_indent' => true,
		'phpdoc_scalar' => true,
		'phpdoc_separation' => true,
		'phpdoc_trim' => true,
		'phpdoc_types' => true,
		'single_blank_line_at_eof' => true,
		'single_import_per_statement' => true,
		'single_line_after_imports' => true,
		'single_quote' => true,
		'space_after_semicolon' => true,
		'standardize_not_equals' => true,
		'switch_case_space' => true,
		'ternary_operator_spaces' => true,
		'trim_array_spaces' => true,
		'unary_operator_spaces' => true,
		'whitespace_after_comma_in_array' => true,
	])
	->setFinder($finder)
	->setUsingCache(true)
	->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
