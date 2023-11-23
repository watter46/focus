<?php declare(strict_types=1);

namespace App\UseCases\Development\Domain;

use Exception;


final readonly class DevelopmentCommand
{
    private function __construct(
        private ?string $developmentId = null,
        private ?string $projectId = null,
        private ?int    $defaultTime = null,
        private ?int    $remainingTime = null,
        private ?array  $selectedIdList = null
    ) {
        //
    }

    public static function findByProjectId(string $projectId): self
    {
        return new self(projectId: $projectId);
    }

    public static function findByDevelopmentId(string $developmentId): self
    {
        return new self(developmentId: $developmentId);
    }

    public static function start(string $projectId, int $defaultTime, array $selectedIdList): self
    {
        return new self(
            projectId: $projectId,
            defaultTime: $defaultTime,
            remainingTime: $defaultTime,
            selectedIdList: $selectedIdList
        );
    }

    public static function stop(string $developmentId, int $remainingTime): self
    {
        return new self(
            developmentId: $developmentId,
            remainingTime: $remainingTime
        );
    }

    public static function changeTask(string $developmentId, array $selectedIdList): self
    {
        return new self(
            developmentId:  $developmentId,
            selectedIdList: $selectedIdList
        );
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
