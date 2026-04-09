<?php

namespace StarterSolutions\InertiaDataTable\Sorting;

use Illuminate\Database\Eloquent\Builder;

class SortQueryBuilder
{
    public function apply(Builder $query, SortResolution $sort): Builder
    {
        if (count($sort->resolvedModelChain) === 1) {
            return $query->orderBy($sort->column, $sort->direction);
        }

        $baseModel = $sort->resolvedModelChain[0];
        $resolvedModel = $sort->resolvedModel;

        $baseTable = $baseModel->getTable();
        $targetTable = $resolvedMsodel->getTable();

        return $query->orderBy(
            $targetModel->newQuery()
                ->select($sort->column)
                ->whereColumn(
                    "{$targetTable}.id",
                    "{$baseTable}.{$this->guessForeignKey($targetModel)}"
                )
                ->limit(1),
            $sort->direction
        );
    }
}