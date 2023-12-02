<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Development as EqDevelopment;
use App\UseCases\Development\DevelopmentCommand;
use App\UseCases\Development\Infrastructure\DevelopmentFactory;
use App\UseCases\Development\Infrastructure\DevelopmentModelBuilder;


final readonly class CompleteDevelopmentUseCase
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
            
            $completed = $this
                ->factory
                ->reconstruct($model)
                ->complete();
                        
            $development = $this->builder->toModel($completed, $model);

            DB::transaction(function () use ($development, $completed) {
                $development->finish($completed);
            });

            return $development;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}