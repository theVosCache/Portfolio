<?php

declare(strict_types=1);

namespace App\Common\Domain\Entity;

abstract class AbstractEntity
{
    public function __construct()
    {
        foreach (class_uses($this) as $trait) {
            $classParts = explode("\\", $trait);
            $baseName = $classParts[count($classParts) - 1];
            $this->{"boot" . $baseName}();
        }
    }
}
