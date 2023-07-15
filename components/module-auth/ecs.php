<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalInternalClassFixer;
use PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void
{
    $ecsConfig->paths([
        'src', 'tests'
    ]);

    $ecsConfig->skip([
        'src/Kernel.php',
        'tests/bootstrap.php',
        'test/PrivatePropertyManipulator.php'
    ]);

    $ecsConfig->sets([SetList::PSR_12]);

    $ecsConfig->rule(ArraySyntaxFixer::class);
    $ecsConfig->rule(DeclareStrictTypesFixer::class);
    $ecsConfig->rule(StrictComparisonFixer::class);
    $ecsConfig->rule(StrictParamFixer::class);
    $ecsConfig->rule(ReturnTypeDeclarationFixer::class);
    $ecsConfig->rule(FinalInternalClassFixer::class);
    $ecsConfig->ruleWithConfiguration(ConcatSpaceFixer::class, [
        'spacing' => 'one'
    ]);
};
