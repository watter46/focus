<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Development;
use App\UseCases\Development\DevelopmentCommand;
use App\UseCases\Development\Infrastructure\DevelopmentModelBuilder;
use App\UseCases\Development\Infrastructure\DevelopmentFactory;


final readonly class ChangeTaskUseCase
{
    public function __construct(private DevelopmentFactory $factory, private DevelopmentModelBuilder $builder)
    {
        //
    }

    public function execute(DevelopmentCommand $command): Development
    {
        try {
            /** @var Development $model */
            $model = Development::findOrFail($command->developmentId());
            
            $changed = $this
                ->factory
                ->reconstruct($model)
                ->changeTask($command);
                        
            $development = $this->builder->toModel($changed, $model);
                                
            DB::transaction(function () use ($development) {
                $development->save();
            });
            
            return $development;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}