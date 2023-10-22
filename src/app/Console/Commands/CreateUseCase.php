<?php declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateUseCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:uc {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make:uc {name  : DirName/FileName -> UseCases/DirName/FileNameUseCase.php}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        
        $fileName = Str::afterLast($name, '/');
        $dirName  = Str::beforeLast($name, '/');
        
        $newFileName = $fileName . 'UseCase';
        
        $path = app_path('UseCases') . '/' . $dirName . '/' . $newFileName . '.php';

        if (File::exists($path)) {
            $this->error("Already Exist ($path)");
            return;
        }
        
        $this->DirExistsOrMake($dirName);

        $nameSpace = Str::of($dirName)->replace('/', '\\')->value();
        $className = Str::studly($newFileName);

        File::put($path, $this->generateClass($nameSpace, $className));
    }

    /**
     * Generate the use case class.
     *
     * @param string $nameSpace
     * @param string $className
     * @return string
     */
    private function generateClass(string $nameSpace, string $className): string
    {
        $tmp = File::get(app_path('Console/Commands/UseCaseTemplate.txt'));

        return Str::of($tmp)
                    ->replace('{$nameSpace}', $nameSpace)
                    ->replace('{$className}', $className)
                    ->value();
    }

    private function dirExistsOrMake($dirName): void
    {
        $useCasePath = app_path('UseCases');
        
        if (!File::exists($useCasePath)) {
            File::makeDirectory($useCasePath);
        }

        $dirPath = $useCasePath . '/' . $dirName;

        if (!File::exists($dirPath)) {
            File::makeDirectory($dirPath);
        }
    }
}