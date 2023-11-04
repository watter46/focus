<?php declare(strict_types=1);

namespace App\Console\Commands\CreateUseCase;

use Illuminate\Console\Command;

use App\Console\Commands\CreateUseCase\UseCaseGenerator;


final class CreateUseCase extends Command
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
        $arg = $this->argument('name');

        $generator = UseCaseGenerator::setup($arg);
        
        if ($generator->fileExists()) {
            $this->error("Already Exist ($arg)");
            return;
        }

        $generator->execute();

        $this->info("UseCase Created!!");
    }
}