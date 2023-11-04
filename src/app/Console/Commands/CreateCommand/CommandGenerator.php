<?php declare(strict_types=1);

namespace App\Console\Commands\CreateCommand;

use App\Console\Commands\ClassGenerator;


final readonly class CommandGenerator extends ClassGenerator
{
    public const ROOT_DIR_NAME = 'UseCases';
    public const COMMAND_NAME  = 'Command';
    public const TEMPLATE_PATH = 'Console/Commands/templates/CommandTemplate.txt';

    private function __construct(private string $arg)
    {
        Parent::__construct(
            arg        : $arg,
            rootDirName: self::ROOT_DIR_NAME,
            commandName: self::COMMAND_NAME,
            template   : self::TEMPLATE_PATH
        );
    }
    
    public static function setup(string $arg)
    {
        return new self($arg);
    }
}