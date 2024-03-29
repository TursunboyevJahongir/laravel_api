<?php

namespace App\Repositories;


use App\Models\Resource;
use Illuminate\Database\Eloquent\Model;

class ResourceRepository
{
    /**
     * @param Resource $model
     */
    public function __construct(protected Resource $model)
    {
    }

    public function removeFile(Model $model)
    {
        \Storage::disk('public')->delete($model->path_original);
        \Storage::disk('public')->delete($model->path_1024);
        \Storage::disk('public')->delete($model->path_512);
    }

    public function create(
        $relation,
        $type,
        $identifier,
        $path_original,
        $displayName = null,
        $path_1024 = null,
        $path_512 = null
    ) {
        $relation->create(['path_original'         => $path_original,
                           'path_1024'             => $path_1024,
                           'path_512'              => $path_512,
                           'type'                  => $type,
                           'additional_identifier' => $identifier,
                           'display_name'          => $displayName,
                          ]);
    }

    public function firstBy(int $modelId): ?Model
    {
        return $this->model->findOrFail($modelId);
    }

    public function destroy(array $images)
    {
        foreach ($images as $image) {
            $this->delete($this->firstBy($image));
        }
    }

    public function delete(Model $image)
    {
        $this->removeFile($image);
        $image->delete();
    }


}
