<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;

use App\Livewire\Utils\Label\Enum\LabelType;


final readonly class ProjectCommand
{
    private function __construct(
        private ?string    $projectId   = null,
        private ?string    $projectName = null,
        private ?LabelType $label       = null,
        private ?string    $name        = null,
        private ?string    $content     = null,
    ) {
        //
    }

    public static function find(string $projectId): self
    {
        return new self(projectId: $projectId);
    }

    public static function newProject(
        string    $projectName,
        LabelType $label,
        string    $name,
        string    $content): self
    {
        return new self(
            projectName: $projectName,
            label:       $label,
            name:        $name,
            content:     $content
        );
    }

    public static function updateProjectName(
        string $projectId,
        string $projectName): self
    {
        return new self(
            projectId:   $projectId,
            projectName: $projectName
        );
    }

    public static function updateLabel(
        string $projectId,
        LabelType $label): self
    {
        return new self(
            projectId: $projectId,
            label:     $label
        );
    }

    public static function addTask(
        string $projectId,
        string $name,
        string $content): self
    {
        return new self(
            projectId: $projectId,
            name:      $name,
            content:   $content
        );
    }

    public function projectId(): string
    {
        if (!$this->projectId) {
            throw new Exception('プロジェクトIDがありません。');
        }
        
        return $this->projectId;
    }

    public function projectName(): ?string
    {
        if (!$this->projectName) {
            throw new Exception('プロジェクト名がありません。');
        }

        return $this->projectName;
    }

    public function label(): ?LabelType
    {
        if (!$this->label) {
            throw new Exception('ラベルがありません。');
        }

        return $this->label;
    }

    public function name(): ?string
    {
        if (!$this->name) {
            throw new Exception('タスク名がありません。');
        }

        return $this->name;
    }

    public function content(): ?string
    {
        if (!$this->content) {
            throw new Exception('タスク内容がありません。');
        }

        return $this->content;
    }
}
