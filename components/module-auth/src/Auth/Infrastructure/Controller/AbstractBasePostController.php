<?php

namespace App\Auth\Infrastructure\Controller;

abstract class AbstractBasePostController extends AbstractBaseController
{
    protected array $data = [];

    public function getDataFields(): array
    {
        return $this->dataFields;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}