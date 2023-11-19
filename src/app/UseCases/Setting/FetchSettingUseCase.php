<?php declare(strict_types=1);

namespace App\UseCases\Setting;

use Exception;

use App\Models\Setting;
use App\UseCases\Setting\Domain\SettingEntity;


final readonly class FetchSettingUseCase
{    
    public function __construct(private SettingEntity $entity)
    {
        //
    }

    public function execute(): Setting
    {
        try {
            /** @var Setting $setting */
            $setting = Setting::latest()->first();
            
            return $setting ?? $this->entity->create()->toModel();

        } catch (Exception $e) {
            throw $e;
        }
    }
}