<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CreateServiceAndResource extends Command
{
    protected $signature = 'make:service-resource {name}';
    protected $description = 'Create a new Service and Resource with folder structure';

    public function handle()
    {
        $name = $this->argument('name');
        $this->createService($name);
        $this->createResource($name);
        $this->info("Service and Resource for {$name} created successfully!");
    }

    protected function createService($name)
    {
        $servicePath = app_path("Services/{$name}Service.php");
        $namespace = "App\\Services";
        $directory = dirname($servicePath);

        // إنشاء المجلد إذا لم يكن موجودًا
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // قالب ملف Service
        $stub = $this->getServiceStub();
        $stub = str_replace(['{{namespace}}', '{{class}}'], [$namespace, "{$name}Service"], $stub);

        // كتابة الملف
        File::put($servicePath, $stub);
        $this->info("Service created: {$servicePath}");
    }

    protected function createResource($name)
    {
        $resourcePath = app_path("Http/Resources/{$name}Resource.php");
        $namespace = "App\\Http\\Resources";
        $directory = dirname($resourcePath);

        // إنشاء المجلد إذا لم يكن موجودًا
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // قالب ملف Resource
        $stub = $this->getResourceStub();
        $stub = str_replace(['{{namespace}}', '{{class}}'], [$namespace, "{$name}Resource"], $stub);

        // كتابة الملف
        File::put($resourcePath, $stub);
        $this->info("Resource created: {$resourcePath}");
    }

    protected function getServiceStub()
    {
        return <<<EOT
<?php

namespace {{namespace}};

class {{class}}
{
    public function __construct()
    {
        //
    }
}
EOT;
    }

    protected function getResourceStub()
    {
        return <<<EOT
<?php

namespace {{namespace}};

use Illuminate\Http\Resources\Json\JsonResource;

class {{class}} extends JsonResource
{
    public function toArray(\$request)
    {
        return parent::toArray(\$request);
    }
}
EOT;
    }
}