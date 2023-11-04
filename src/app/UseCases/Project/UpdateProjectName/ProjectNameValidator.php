<?php declare(strict_types=1);

namespace App\UseCases\Project\UpdateProjectName;

use Exception;
use Illuminate\Support\Str;


final readonly class ProjectNameValidator
{
    public function __construct(private string $projectName)
    {
        if (Str::length($projectName) > 50) {
            throw new Exception('プロジェクト名は50文字以下です。');
        }
    }

    public function projectName(): array
    {
        return ['project_name' => $this->projectName];
    }
}