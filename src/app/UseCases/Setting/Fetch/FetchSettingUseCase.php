<?php declare(strict_types=1);

namespace App\UseCases\Setting\Fetch;

use Illuminate\Support\Collection;

use App\Models\Setting;


final readonly class FetchSettingUseCase
{
    const DEFAULT_TIME = 30;
    
    public function __construct()
    {
        //
    }

    public function execute(): Setting
    {
        /** @var Collection $setting */
        $setting = Setting::get();

        if ($setting->isEmpty()) {
            return (new Setting)->createSetting();
        }

        return $setting->first();
    }
}