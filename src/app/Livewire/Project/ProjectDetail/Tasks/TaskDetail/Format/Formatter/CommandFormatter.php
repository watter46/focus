<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatter;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatted;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\FormatterInterface;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Splitted;


final class CommandFormatter implements FormatterInterface
{
    public const UNCHECKED = '- [ ] ';
    public const CHECKED   = '- [|] ';
    public const COMMAND_LENGTH = 6;
    
    /**
     * コマンドを変換
     *
     * @param  string    $content
     * @param  Formatted $formatted
     * @param  int       $index
     * @param  Splitted  $splitted
     * @return Formatted
     */
    public function format(string $content, Formatted $formatted, int $index, Splitted $splitted): Formatted
    {
        if ($splitted->canAddCommand($index)) {
            return $formatted->addCommand($this->convertCommand($content));
        }

        $command = collect(['ul' => collect([
            $this->convertCommand($content)
        ])]);

        return $formatted->add($command);
    }
    
    /**
     * フォーマッターに対応しているか判定する
     *
     * @param  string $content
     * @return bool
     */
    public function supports(string $content): bool
    {
        return Str::startsWith($content, [
            self::UNCHECKED,
            self::CHECKED
        ]);
    }

    /**
     * コマンドを変換
     *
     * @param  string $content
     * @return Collection
     */
    private function convertCommand(string $content): Collection
    {
        $command = Str::of($content)->substr(0, self::COMMAND_LENGTH)->toString();

        return match ($command) {
            self::UNCHECKED => collect([
                'command'   => Str::after($content, self::UNCHECKED),
                'isChecked' => false
            ]),
            self::CHECKED => collect([
                'command'   => Str::after($content, self::CHECKED),
                'isChecked' => true
            ])
        };
    }
}