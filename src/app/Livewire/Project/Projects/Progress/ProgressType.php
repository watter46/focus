<?php declare(strict_types=1);

namespace App\Livewire\Project\Projects\Progress;


enum ProgressType: string
{
    case All        = 'all';
    case Completed  = 'completed';
    case Unselected = '';
}