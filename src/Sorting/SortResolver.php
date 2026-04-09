<?php

namespace StarterSolutions\InertiaDataTable\Sorting;

use Illuminate\Database\Eloquent\Model;

class SortResolver
{
    public function resolve($query, string $sortBy, string $direction): SortResolution
    {
        /** @var Model $baseModel */
        $baseModel = $query->getModel();
        $baseTable = $baseModel->getTable();

        if (!str_contains($sortBy, '.')) {
            return new SortResolution(
                column: $sortBy,
                direction: $direction,
                baseModel: $baseModel::class,
                baseTable: $baseTable,
                relationChain: [],
                resolvedModelChain: [$baseModel],
                resolvedModel: $baseModel,
            );
        }

        $segments = explode('.', $sortBy);
        $column = array_pop($segments);

        return $this->walk(
            model: $baseModel,
            segments: $segments,
            column: $column,
            direction: $direction,
            relationChain: [],
            modelChain: [$baseModel],
        );
    }

    private function walk(Model $model, array $segments, string $column, string $direction, array $relationChain, array $modelChain): SortResolution
    {
        $relationName = array_shift($segments);

        $relation = $model->{$relationName}();
        $nextModel = $relation->getRelated();

        $relationChain[] = $relationName;
        $modelChain[] = $nextModel;

        if (empty($segments)) {
            return new SortResolution(
                column: $column,
                direction: $direction,
                baseModel: $modelChain[0]::class,
                baseTable: $modelChain[0]->getTable(),
                relationChain: $relationChain,
                resolvedModelChain: $modelChain,
                resolvedModel: $nextModel,
            );
        }

        return $this->walk(
            model: $nextModel,
            segments: $segments,
            column: $column,
            direction: $direction,
            relationChain: $relationChain,
            modelChain: $modelChain,
        );
    }
}