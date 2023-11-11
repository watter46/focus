<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label;

use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\Presenters\DisplayLabelPresenter;
use App\Livewire\Utils\Label\Presenters\SelectLabelPresenter;
use App\Livewire\Utils\Label\Presenters\SortLabelPresenter;
use App\Livewire\Utils\Label\LabelPresenterResolver;
use App\Livewire\Utils\Label\ReadLabelInterface;
use App\Livewire\Utils\Label\LabelInterface;


final readonly class LabelCommand
{
    public function __construct(private readonly LabelPresenterResolver $resolver)
    {
        $resolver
            ->add(new DisplayLabelPresenter)
            ->add(new SelectLabelPresenter)
            ->add(new SortLabelPresenter);
    }

    public function execute(PurposeType $purpose): ReadLabelInterface|LabelInterface
    {
        return $this->resolver->resolve($purpose);
    }
}