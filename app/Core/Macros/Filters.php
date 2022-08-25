<?php

use App\Enums\AvailableLocalesEnum;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\{
    Eloquent\Builder as EloquentBuilder,
    Query\Builder as QueryBuilder
};

/*
                                                  .~))>>
                                                 .~)>>
                                               .~))))>>>
                                             .~))>>             ___
                                           .~))>>)))>>      .-~))>>
                                         .~)))))>>       .-~))>>)>
                                       .~)))>>))))>>  .-~)>>)>
                   )                 .~))>>))))>>  .-~)))))>>)>
                ( )@@*)             //)>))))))  .-~))))>>)>
              ).@(@@               //))>>))) .-~))>>)))))>>)>
            (( @.@).              //))))) .-~)>>)))))>>)>
          ))  )@@*.@@ )          //)>))) //))))))>>))))>>)>
       ((  ((@@@.@@             |/))))) //)))))>>)))>>)>
      )) @@*. )@@ )   (\_(\-\b  |))>)) //)))>>)))))))>>)>
    (( @@@(.@(@ .    _/-  ~|b |>))) //)>>)))))))>>)>
     )* @@@ )@*     (@)  (@) /\b|))) //))))))>>))))>>
   (( @. )@( @ .   _/  /    /  \b)) //))>>)))))>>>_._
    )@@ (@@*)@@.  (6///6)- / ^  \b)//))))))>>)))>>   -.
 ( @jgs@@. @@@.*@_ VvvvvV//  ^  \b/)>>))))>>      _.     `bb
  ((@@ @@@*.(@@ . - | o |' \ (  ^   \b)))>>        .'       b`,
   ((@@).*@@ )@ )   \^^^/  ((   ^  ~)_        \  /           b `,
     (@@. (@@ ).     `-'   (((   ^    `\ \ \ \ \|             b  `.
       (*.@*              / ((((        \| | |  \       .       b `.
                         / / (((((  \    \ /  _.-~\     Y,      b  ;
                        / / / (((((( \    \.-~   _.`" _.-~`,    b  ;
                       /   /   `(((((()    )    (((((~      `,  b  ;
                     _/  _/      `"""/   /'                  ; b   ;
                 _.-~_.-~           /  /'                _.'~bb _.'
               ((((              / /'              _.'~bb.--~
                                  ((((          __.-~bb.-~
                                              .'  b .
                                              :bb ,'
                                              ~~
*/
/**
 * to filter filters[0][status]=activated&filters[0][name]="Jahongir"
 *
 * @param string $boolean
 */
EloquentBuilder::macro('filters', function ($filters = null, string $boolean = 'and') {
    $filters = $filters ?? request()->get('filters');
    $this->when($filters, function (EloquentBuilder $query) use ($filters, $boolean) {
        $filters = $filters[array_key_first($filters)];
        foreach ($filters as $key => $filter) {
            if ($this->model->isSearchable($key)) {
                if ($this->model->isJson($key)) {
                    $query->where(function ($query) use ($key, $filter) {
                        foreach (AvailableLocalesEnum::toArray() as $lang) {
                            $query->orWhereLike("$key->$lang", $filter);
                        }
                    }, boolean: $boolean);
                } elseif ($this->model->inDates($key)) {
                    $time = Carbon::createFromTimestamp(strtotime($filter));
                    $query->whereDate($key, $time, $boolean);
                } else {
                    $query->orWhereLike($key, $filter);
                }
            } elseif (in_array($key, $this->model->getDates(), true)) {
                $time = Carbon::createFromTimestamp(strtotime($filter));
                $query->whereDate($key, $time, $boolean);
            } elseif ($key === "id" || is_array($filter)) {
                $filter = is_array($filter) ? $filter : explode(',', $filter);
                $query->whereIn($key, $filter, boolean: $boolean);
            } elseif (str_contains($key, '.')) {
                $relation = explode('.', $key);
                $column   = array_pop($relation);
                $query->whereInRelation(implode('.', $relation), $column, Arr::wrap($filter), $boolean);
            } else {
                $query->where($key, '=', $filter, boolean: $boolean);
            }
        }
    });

    return $this;
});

QueryBuilder::macro('filters', function ($filters = null, string $boolean = 'and') {
    $filters = $filters ?? request()->get('filters');
    $this->when($filters, function (EloquentBuilder|QueryBuilder $query) use ($filters, $boolean) {
        $filters = $filters[array_key_first($filters)];
        foreach ($filters as $key => $filter) {
            $query->where($key, '=', $filter, boolean: $boolean);
        }
    });

    return $this;
});

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    /**
     * not equal
     * not filter not_filters[0][status]=activated
     */
    $builder::macro('orFilters', function () {
        $this->filters(request('or_filters', 0), 'or');

        return $this;
    });

    /**
     * or filter
     * or_filters[0][first_name]=Jahongir&or_filters[0][last_name]=Jahongir&or_filters[0][middle_name]=Jahongir
     */
    $builder::macro('notFilters', function () {
        $this->whereNot(fn($q) => $q->filters(request('not_filters', 0)));

        return $this;
    });
}
