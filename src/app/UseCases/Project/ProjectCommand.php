<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;

use App\Livewire\Utils\Label\Enum\LabelType;


final readonly class ProjectCommand
{
    public function __construct(
        private ?string    $projectId = null,
        private ?string    $projectName = null,
        private ?LabelType $label = null,
        private ?string    $name = null,
        private ?string    $content = null,
    ) {
        //
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
