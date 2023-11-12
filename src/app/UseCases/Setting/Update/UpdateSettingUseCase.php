<?php declare(strict_types=1);

namespace App\UseCases\Setting\Update;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Setting;
use App\UseCases\Setting\SettingCommand;


final readonly class UpdateSettingUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(SettingCommand $command): Setting
    {
        try {
            /** @var Setting $setting */
            $setting = Setting::get()->first() ?? new Setting;
            
            $setting
                ->updateSetting(
                    defaultTime: $command->defaultTime(),
                    breakTime: $command->breakTime()
                );

            DB::transaction(function () use ($setting) {
                $setting->save();
            });

            return $setting;

        } catch (Exception $e) {
            throw $e;
        }
    }
}