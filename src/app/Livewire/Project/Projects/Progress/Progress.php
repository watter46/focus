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

    public static function get(ProgressType $current, string $selectedProgress): ProgressType
    {
        $selected = ProgressType::tryFrom($selectedProgress);

        if (!$selected) {
            throw new Exception('不正なProgressタイプです。');
        }
        
        return $current === $selected
                    ? ProgressType::Unselected
                    : $selected;
    }
}