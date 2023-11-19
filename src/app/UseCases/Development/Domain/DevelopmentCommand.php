<?php declare(strict_types=1);

namespace App\UseCases\Development\Domain;

use Exception;


final readonly class DevelopmentCommand
{
    public function __construct(
        private ?string $projectId = null,
        private ?string $developmentId = null,
        private ?int    $defaultTime = null,
        private ?int    $remainingTime = null,
        private ?array  $selectedIdList = null
    ) {
        //
    }

    public function projectId(): string
    {
        if (!$this->projectId) {
            throw new Exception('プロジェクトIDが存在しません。');
        }
        
        return $this->projectId;
    }

    public function developmentId(): string
    {
        if (!$this->developmentId) {
            throw new Exception('開発IDが存在しません。');
        }
        
        return $this->developmentId;
    }

    public function defaultTime(): int
    {
        if (!$this->defaultTime) {
            throw new Exception('デフォルト時間が存在しません。');
        }

        return $this->defaultTime;
    }

    public function remainingTime(): int
    {
        if (!$this->remainingTime) {
            throw new Exception('残り時間が存在しません。');
        }

        return $this->remainingTime;
    }

    public function selectedIdList(): array
    {
        if (!$this->selectedIdList) {
            throw new Exception('タスクIDリストが存在しません。');
        }

        return $this->selectedIdList;
    }
}
