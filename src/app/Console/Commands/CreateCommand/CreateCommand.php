<?php declare(strict_types=1);

namespace App\Console\Commands\CreateCommand;

use Illuminate\Console\Command;

use App\Console\Commands\CreateCommand\CommandGenerator;


final class CreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:com {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make:com {name  : DirName/FileName -> UseCases/DirName/FileNameCommand.php}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $arg = $this->argument('name');

        $generator = CommandGenerator::setup($arg);
        
        if ($generator->fileExists()) {
            $this->error("Already Exist ($arg)");
            return;
        }

        $generator->execute();

        $this->info("Command Created!!");
    }
}