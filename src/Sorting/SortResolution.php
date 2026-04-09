<?php

namespace StarterSolutions\InertiaDataTable\Sorting;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $column
 * @property string $direction
 * @property string $baseModel
 * @property string $baseTable
 * @property string[] $relationChain
 * @property Model[] $resolvedModelChain
 * @property Model|null $relatedModel
 */
class SortResolution
{
    public function __construct(
        public string $column,
        public string $direction,

        /** @var class-string<Model> */
        public string $baseModel,

        public string $baseTable,

        /** @var string[] */
        public array $relationChain,

        /** @var Model[] */
        public array $resolvedModelChain,

        public ?Model $resolvedModel,
    ) {}
}