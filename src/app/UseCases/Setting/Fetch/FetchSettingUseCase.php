<?php declare(strict_types=1);

namespace App\UseCases\Setting\Fetch;

use Exception;
use Illuminate\Support\Collection;

use App\Models\Setting;
use App\UseCases\Setting\SettingEntity;


final readonly class FetchSettingUseCase
{    
    public function __construct(private SettingEntity $entity)
    {
        //
    }

    public function execute(): Setting
    {
        try {
            /** @var Collection $setting */
            $setting = Setting::latest()->first();

            return $setting ?? $this->entity->create()->toModel();

        } catch (Exception $e) {
            throw $e;
        }
    }
}