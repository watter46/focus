<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Development as EqDevelopment;
use App\UseCases\Development\DevelopmentCommand;
use App\UseCases\Development\Infrastructure\DevelopmentFactory;
use App\UseCases\Development\Infrastructure\DevelopmentModelBuilder;


final readonly class ClearDevelopmentUseCase
{
    public function __construct(private DevelopmentFactory $factory, private DevelopmentModelBuilder $builder)
    {
        //
    }

    public function execute(DevelopmentCommand $command): EqDevelopment
    {
        try {
            /** @var EqDevelopment $model */
            $model = EqDevelopment::findOrFail($command->developmentId());
            
            $cleared = $this
                ->factory
                ->reconstruct($model)
                ->clear();

            $development = $this->builder->toModel($cleared, $model);
            
            DB::transaction(function () use ($development, $cleared) {
                $development->finish($cleared);
            });

            return $development;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}