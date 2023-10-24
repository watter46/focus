<?php declare(strict_types=1);

namespace App\Console\Commands\CreateUseCase;

use App\Console\Commands\ClassGenerator;


final readonly class UseCaseGenerator extends ClassGenerator
{
    public const ROOT_DIR_NAME = 'UseCases';
    public const COMMAND_NAME  = 'UseCase';
    public const TEMPLATE_PATH = 'Console/Commands/templates/UseCaseTemplate.txt';

    public const SUB_COMMAND_NAME = 'Command';

    private function __construct(private string $arg)
    {
        Parent::__construct(
            arg           : $arg,
            rootDirName   : self::ROOT_DIR_NAME,
            commandName   : self::COMMAND_NAME,
            template      : self::TEMPLATE_PATH,
            subCommandName: self::SUB_COMMAND_NAME
        );
    }
    
    public static function setup(string $arg)
    {
        return new self($arg);
    }
}