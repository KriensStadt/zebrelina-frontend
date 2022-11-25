<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);

    $ecsConfig->ruleWithConfiguration(ConcatSpaceFixer::class, [
        'spacing' => 'one'
    ]);

    $ecsConfig->skip([MethodArgumentSpaceFixer::class]);

    $ecsConfig->sets([
        // run and fix, one by one
        SetList::PSR_12,
        SetList::CLEAN_CODE,
    ]);
};
