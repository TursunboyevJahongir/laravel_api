<?php

namespace App\Repositories;


use App\Contracts\ResourceRepositoryContract;
use App\Core\Models\CoreModel;
use App\Models\Resource;

class ResourceRepository implements ResourceRepositoryContract
{
    /**
     * @param Resource $model
     */
    public function __construct(protected Resource $model)
    {
    }

    public function removeFile(CoreModel $model)
    {
        @unlink(public_path($model->path_original));
        @unlink(public_path($model->path_1024));
        @unlink(public_path($model->path_512));
    }

    public function create($relation, $type, $identifier, $path_original, $path_1024 = null, $path_512 = null)
    {
        $relation->create(['path_original'         => $path_original,
                           'path_1024'             => $path_1024,
                           'path_512'              => $path_512,
                           'type'                  => $type,
                           'additional_identifier' => $identifier
                          ]);
    }

    public function findById(int $modelId): ?CoreModel
    {
        return $this->model->findOrFail($modelId);
    }

    public function destroy(array $images)
    {
        foreach ($images as $image) {
            $this->delete($this->findById($image));
        }
    }

    public function delete(CoreModel $image)
    {
        $this->removeFile($image);
        $image->delete();
    }


}
