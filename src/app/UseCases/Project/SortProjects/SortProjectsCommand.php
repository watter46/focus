<?php declare(strict_types=1);

namespace App\UseCases\Project\SortProjects;

use Illuminate\Support\Collection;


final readonly class SortProjectsCommand
{
    public function __construct(private readonly Collection $options)
    {
        //
    }

    public function process(): string
    {
        return $this->options->get('progress');
    }

    public function label(): string
    {
        return $this->options->get('label');
    }
}