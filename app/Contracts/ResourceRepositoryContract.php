<?php

namespace App\Contracts;


use App\Core\Models\CoreModel;

interface ResourceRepositoryContract
{
    /**
     * deleting files from memory
     * @param CoreModel $model
     */
    public function removeFile(CoreModel $model);

    public function create($relation, $type, $identifier, $path_original, $path_1024 = null, $path_512 = null);

    public function findById(int $modelId): ?CoreModel;

    public function destroy(array $images);

    public function delete(CoreModel $image);
}
