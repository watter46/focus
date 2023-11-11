<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label;

use Exception;
use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\PurposeType;


final readonly class LabelPresenterResolver
{
    /** @var Collection<int, LabelInterface|ReadLabelInterface> $presenters */
    private Collection $presenters;

    public function __construct()
    {
        $this->presenters = collect();
    }

    public function add(LabelInterface|ReadLabelInterface $label): self
    {
        $this->presenters->push($label);

        return $this;
    }

    public function resolve(PurposeType $purpose): LabelInterface|ReadLabelInterface
    {
        foreach($this->presenters as $presenter) {
            if ($presenter->supports($purpose)) {
                return $presenter;
            }
        }

        throw new Exception('使用用途に対応していません。:' . $purpose->name);
    }
}