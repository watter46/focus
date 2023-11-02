<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;
use Illuminate\Support\Str;

use App\Livewire\Utils\Label\Enum\LabelType;

final readonly class ProjectCommand
{
    public function __construct(
        private string $projectId,
        private ?string $name = null,
        private ?LabelType $label = null
    ) {
        if (!Str::isUlid($this->projectId)) {
            throw new Exception('プロジェクトIDが無効です。');
        }
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function label(): ?LabelType
    {
        return $this->label;
    }
}
