<?php declare(strict_types=1);

namespace App\UseCases\Setting\Update;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Setting;
use App\UseCases\Setting\SettingCommand;
use App\UseCases\Setting\SettingEntity;


final readonly class UpdateSettingUseCase
{
    public function __construct(private SettingEntity $entity)
    {
        //
    }

    public function execute(SettingCommand $command): Setting
    {
        try {
            /** @var Setting $setting */
            $setting = Setting::get()->first();

            $updated = $setting
                ? $this
                    ->entity
                    ->reconstruct($setting)
                    ->update($command)
                    ->toModel()
                : $this
                    ->entity
                    ->update($command)
                    ->toModel();

            DB::transaction(function () use ($updated) {
                $updated->save();
            });

            return $updated;

        } catch (Exception $e) {
            throw $e;
        }
    }
}