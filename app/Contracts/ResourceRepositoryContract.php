<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ResourceRepositoryContract
{
    /**
     * deleting files from memory
     *
     * @param Model $model
     */
    public function removeFile(Model $model);

    public function create($relation, $type, $identifier, $path_original, $path_1024 = null, $path_512 = null);

    public function findById(int $modelId): ?Model;

    public function destroy(array $images);

    public function delete(Model $image);
}
