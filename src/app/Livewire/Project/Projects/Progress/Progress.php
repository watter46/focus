<?php declare(strict_types=1);

namespace App\Livewire\Project\Projects\Progress;

use Exception;

use App\Livewire\Project\Projects\Progress\ProgressType;


final readonly class Progress
{
    public function __construct()
    {
        //
    }

    public static function get(string $currentProgress, string $selectedProgress): ProgressType
    {
        $current  = ProgressType::tryFrom($currentProgress);
        $selected = ProgressType::tryFrom($selectedProgress);

        if (!$current || !$selected) {
            throw new Exception('不正なProgressタイプです。');
        }

        $isSame = $current === $selected;
        
        return $isSame 
                ? ProgressType::Unselected
                : $selected;
    }
}