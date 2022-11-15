<?php

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

//namespace QueryBuilder {
//
//
//    class Builder {}
//}

EloquentBuilder::macro('whereConditions', function (array $conditions = null, string $boolean = 'and') {
    $this->when($conditions, function (EloquentBuilder $query) use ($conditions, $boolean) {
        foreach ($conditions as $column => $value) {
            //if ($this->isSearchable($column)) {  condition like disabled
            //    if (str_contains($column, '.')) {
            //        $relation = explode('.', $column);
            //        $column   = array_pop($relation);
            //        $query->whereHas(implode('.', $relation), function (EloquentBuilder $query) use ($column, $value) {
            //            $query->where($column, like(), $value);
            //        });
            //        //$query->whereInRelation(, $column, Arr::wrap($value), $boolean);
            //    } elseif ($this->isTranslatable($column)) {
            //        $query->where(function ($query) use ($column, $value) {
            //            foreach (config('laravel_api.available_locales', []) as $lang) {
            //                $query->orWhereLike("$column->$lang", $value);
            //            }
            //        }, boolean: $boolean);
            //    } elseif ($this->inDates($column)) {
            //        $time = Carbon::createFromTimestamp(strtotime($value));
            //        $query->whereDate($column, $time, $boolean);
            //    } else {
            //        $query->orWhereLike($column, $value);
            //    }
            //} else
            if ($this->isTranslatable($column)) {
                $query->where(function ($query) use ($column, $value) {
                    foreach (config('laravel_api.available_locales', []) as $lang) {
                        $query->orWhere("$column->$lang", $value);
                    }
                }, boolean: $boolean);
            } elseif ($this->inDates($column)) {
                $time = Carbon::createFromTimestamp(strtotime($value));
                $query->whereDate($column, $time, $boolean);
            } elseif ($column === "id" || is_array($value)) {
                $value = is_array($value) ? $value : explode(',', $value);
                $query->whereIn($column, $value, boolean: $boolean);
            } elseif (str_contains($column, '.')) {
                $relation = explode('.', $column);
                $column   = array_pop($relation);
                $query->whereInRelation(implode('.', $relation), $column, Arr::wrap($value), $boolean);
            } else {
                $query->where($column, '=', $value, boolean: $boolean);
            }
        }
    });

    return $this;
});

QueryBuilder::macro('whereConditions', function (array $conditions = null, string $boolean = 'and') {
    $this->when($conditions, function (QueryBuilder $query) use ($conditions, $boolean) {
        foreach ($conditions as $key => $filter) {
            $query->where($key, '=', $filter, boolean: $boolean);
        }
    });

    return $this;
});

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    /**
     * to filter conditions[status]=activated&conditions[name]="Jahongir"
     *
     * @method mixed conditions(array $conditions = null, string $boolean = 'and')
     */

    $builder::macro('conditions', function (array $conditions = null, string $boolean = 'and') {
        $conditions = $conditions ?? macrosConditions(request(config('laravel_api.params.conditions', 'conditions')));

        return $this->whereConditions($conditions, $boolean);
    });


    /**
     * or condition
     * or_conditions[first_name]=Jahongir&or_conditions[last_name]=Jahongir&or_conditions[author.middle_name]=Jahongir
     * or you can use it on one variable or_condition=first_name:Jahongir;last_name:Jahongir;author.middle_name=Jahongir
     */
    $builder::macro('orConditions', function (array $orConditions = null) {
        $orConditions = $orConditions ?? macrosConditions(request(config('laravel_api.params.or_conditions', 'or_conditions')));

        return $this->whereConditions($orConditions, 'or');
    });

    /**
     * not equal
     * not filter not_conditions[status]=activated
     * or you can use it on one variable not_condition=first_name:Jahongir;author.middle_name=Jahongir
     * .middle_name=Jahongir
     */
    $builder::macro('notConditions', function (array $notConditions = null) {
        $notConditions = $notConditions ?? macrosConditions(request(config('laravel_api.params.not_conditions', 'not_conditions')));

        return $this->whereNot(fn($q) => $q->whereConditions($notConditions));
    });
}
