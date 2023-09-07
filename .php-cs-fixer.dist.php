<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('var');

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'backtick_to_shell_exec' => true,
        'binary_operator_spaces' => true,
        'blank_line_before_statement' => ['statements' => ['return']],
        'braces' => ['allow_single_line_anonymous_class_with_empty_body' => true, 'allow_single_line_closure' => true],
        'cast_spaces' => true,
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'class_definition' => ['single_line' => true],
        'class_reference_name_casing' => true,
        'clean_namespace' => true,
        'concat_space' => ['spacing' => 'one'],
        'echo_tag_syntax' => true,
        'empty_loop_body' => ['style' => 'braces'],
        'empty_loop_condition' => true,
        'fully_qualified_strict_types' => true,
        'function_typehint_space' => true,
        'general_phpdoc_tag_rename' => ['replacements' => ['inheritDocs' => 'inheritDoc']],
        'global_namespace_import' => true,
        'include' => true,
        'increment_style' => ['style' => 'post'],
        'integer_literal_case' => true,
        'lambda_not_used_import' => true,
        'linebreak_after_opening_tag' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'method_argument_space' => ['on_multiline' => 'ignore'],
        'native_function_casing' => true,
        'native_function_type_declaration_casing' => true,
        'no_alias_language_construct_call' => true,
        'no_alternative_syntax' => true,
        'no_binary_string' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => ['tokens' => ['attribute', 'case', 'continue', 'curly_brace_block', 'default', 'extra', 'parenthesis_brace_block', 'square_brace_block', 'switch', 'throw', 'use']],
        'no_trailing_comma_in_singleline' => true,
        'no_unneeded_control_parentheses' => ['statements' => ['break', 'clone', 'continue', 'echo_print', 'others', 'return', 'switch_case', 'yield', 'yield_from']],
        'no_unneeded_curly_braces' => ['namespaces' => true],
        'no_unneeded_import_alias' => true,
        'no_unset_cast' => true,
        'no_unused_imports' => true,
        'no_useless_concat_operator' => true,
        'no_useless_nullsafe_operator' => true,
        'no_whitespace_before_comma_in_array' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
        'php_unit_fqcn_annotation' => true,
        'php_unit_method_casing' => true,
        'phpdoc_align' => true,
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag_normalizer' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_alias_tag' => true,
        'phpdoc_no_package' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order' => ['order' => ['param', 'return', 'throws']],
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => [
            'groups' => [
                ['deprecated', 'link', 'see', 'since'],
                ['author', 'copyright', 'license'],
                ['category', 'package', 'subpackage'],
                ['property', 'property-read', 'property-write'],
                ['ORM*'],
                ['Route'],
                ['param'],
            ],
            'skip_unlisted_annotations' => true,
        ],
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'phpdoc_var_without_name' => true,
        'protected_to_private' => true,
        'semicolon_after_instruction' => true,
        'simple_to_complex_string_variable' => true,
        'single_class_element_per_statement' => true,
        'single_import_per_statement' => true,
        'single_line_comment_spacing' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_line_throw' => true,
        'single_quote' => true,
        'single_space_after_construct' => ['constructs' => ['abstract', 'as', 'attribute', 'break', 'case', 'catch', 'class', 'clone', 'comment', 'const', 'const_import', 'continue', 'do', 'echo', 'else', 'elseif', 'enum', 'extends', 'final', 'finally', 'for', 'foreach', 'function', 'function_import', 'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'match', 'named_argument', 'namespace', 'new', 'open_tag_with_echo', 'php_doc', 'php_open', 'print', 'private', 'protected', 'public', 'readonly', 'require', 'require_once', 'return', 'static', 'switch', 'throw', 'trait', 'try', 'type_colon', 'use', 'use_lambda', 'use_trait', 'var', 'while', 'yield', 'yield_from']],
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],
        'standardize_increment' => true,
        'standardize_not_equals' => true,
        'switch_continue_to_break' => true,
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'types_spaces' => ['space_multiple_catch' => 'single'],
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setCacheFile('.php_cs.cache');
