<?php

namespace App\Core\Helpers\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait OwnGenerator
{
    private function generateOwn($path, $method): void
    {
        $this->makeFolder($path);
        $stub = $this->{$method}();

        $_path = str_replace('\\', '/', $path) . '/' . $this->getFilename($method);
        $this->info('Path: ' . $_path);
        if (!File::exists($_path)) {
            file_put_contents($_path, $stub);
            echo 'File $_path created successfull \n';
        }
    }

    private function createMigration($path)
    {
        $this->makeFolder($path);
        $file = now()->format('Y_m_d_his') . '_create_' . ($table = Str::plural(Str::snake($this->model))) . '_table.php';
        $stub = $this->generateMigration($table);

        $_path = str_replace('\\', '/', $path) . '/' . $file;
        $this->info('Path: ' . $_path);
        if (!File::exists($_path)) {
            file_put_contents($_path, $stub);
            echo 'File $_path created successfull \n';
        }
    }

    private function generateModel(): string
    {
        // Get the model name and fillable fields from class properties
        $modelName      = $this->model;
        $fillableFields = $this->fields;

        // Generate relationships based on field names ending with "_id"
        $relations = '';
        foreach ($fillableFields as $field) {
            if (mb_substr($field, -3) === '_id') {
                $relationName = Str::camel(mb_substr($field, 0, -3));
                $relations    .= "\n\n    public function $relationName(): BelongsTo\n    {\n        return \$this->belongsTo("
                    . Str::studly(mb_substr($field, 0, -3)) . "::class, '$field');\n    }";
            }
        }

        // Generate the stub content
        $stub = "<?php

namespace " . config('modulegenerator.model_path') . ";

use App\Core\Traits\CoreModel;
use App\Traits\Author;
use App\Traits\IsActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class $modelName extends Model
{
    use HasFactory, Author, CoreModel, IsActive;//SoftDeletes

    protected \$fillable = ['" . implode("', '", $fillableFields) . "'];

    protected \$searchable = [];//todo

    $relations
}
";

        return $stub;
    }

    private function generateMigration(string $tableName): string
    {
        // Initialize an empty string to store the column definitions
        $columns = '';

        // Loop through the fields and generate the column definitions
        foreach ($this->fields as $field) {
            // Check if the field name ends with "_id"
            if (substr($field, -3) === '_id') {
                $columns .= "            \$table->foreignId('$field')->constrained();\n";
            } else {
                $columns .= "            \$table->('$field');\n";
            }
        }

        // Generate the stub content
        $stub = "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string \$tableName = '$tableName';

    public function up()
    {
        Schema::create(\$this->tableName, function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('author_id')->constrained('users');
            $columns
            \$table->softDeletes();//todo
            \$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(\$this->tableName);
    }
};
";

        return $stub;
    }

    private function generateValidationCreateRequest(): string
    {
        // Generate the stub content
        $stub = "<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Create{$this->module}Request extends FormRequest
{
    public function rules()
    {
        return [
";

        // Loop through the fields and generate validation rules
        foreach ($this->fields as $field) {
            $stub .= "            '$field' => 'required',\n";
        }

        // Close the stub content
        $stub .= "        ];
    }
}
";

        return $stub;
    }

    private function generateValidationUpdateRequest(): string
    {
        // Generate the stub content
        $stub = "<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Update{$this->module}Request extends FormRequest
{
    public function rules()
    {
        return [
";

        // Loop through the fields and generate validation rules
        foreach ($this->fields as $field) {
            $stub .= "            '$field' => 'filled',\n";
        }

        // Close the stub content
        $stub .= "        ];
    }
}
";

        return $stub;
    }

    private function generateFactory(): string
    {
        $stub = "<?php

namespace Database\Factories;

use " . config('modulegenerator.model_path') . "\\" . $this->model . ";
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class {$this->module}Factory extends Factory
{
    protected \$model = {$this->model}::class;

    public function definition()
    {
        return [
";

        // Loop through the fields and generate field definitions
        foreach ($this->fields as $field) {
            // If field ends with '_id', generate field definition with related model
            if (mb_substr($field, -3) === '_id') {
                $relatedModel = Str::studly(rtrim($field, '_id'));
                $stub         .= "            '$field' => {$relatedModel}::inRandomOrder()->first(),\n";
            } else {
                $stub .= "            '$field' => \$this->faker->name,\n";
            }
        }

        // Add default field definitions
        $stub .= "            'is_active' => \$this->faker->boolean,\n";
        $stub .= "            'author_id' => User::inRandomOrder()->value('id'),\n";

        // Close the stub content
        $stub .= "        ];
    }
}
";

        return $stub;
    }


}
