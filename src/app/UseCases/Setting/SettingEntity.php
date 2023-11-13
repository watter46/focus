<?php declare(strict_types=1);

namespace App\UseCases\Setting;

use App\Models\Setting;
use App\Constants\BreakTimeConstants;
use App\Constants\DefaultTimeConstants;


final class SettingEntity
{
    private int $defaultTime_min;
    private int $breakTime_min;
    
    public function __construct()
    {
        //
    }
        
    /**
     * 初期値を生成する
     *
     * @return self
     */
    public function create(): self
    {
        $this->defaultTime_min = DefaultTimeConstants::VALUE_min;
        $this->breakTime_min   = BreakTimeConstants::VALUE_min;

        return $this;
    }
    
    /**
     * DBから再構築する
     *
     * @param  Setting $setting
     * @return self
     */
    public function reconstruct(Setting $setting): self
    {
        $this->defaultTime_min = $setting->default_time;
        $this->breakTime_min   = $setting->break_time;
        
        return $this;
    }
    
    /**
     * 更新する
     *
     * @param  SettingCommand $command
     * @return self
     */
    public function update(SettingCommand $command): self
    {
        $this->defaultTime_min = $command->defaultTime();
        $this->breakTime_min   = $command->breakTime();

        $this->validate();
        
        return $this;
    }
    
    /**
     * 正常な値か調べる
     *
     * @return void
     */
    public function validate(): void
    {
        DefaultTimeConstants::isValid_min($this->defaultTime_min);
        BreakTimeConstants::isValid_min($this->breakTime_min);
    }
    
    /**
     * モデルに変換する
     *
     * @return Setting
     */
    public function toModel(): Setting
    {
        return (new Setting)->fromEntity(
                $this->defaultTime_min,
                $this->breakTime_min
            );
    }
}