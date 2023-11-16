<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Development;
use App\UseCases\Development\Domain\DevelopmentCommand;


final readonly class CompleteDevelopmentUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(DevelopmentCommand $command): Development
    {
        try {
            /** @var Development $development */
            $development = Development::query()
                                ->findOrFail($command->developmentId())
                                ->toEntity()
                                ->finish()
                                ->toModel();

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