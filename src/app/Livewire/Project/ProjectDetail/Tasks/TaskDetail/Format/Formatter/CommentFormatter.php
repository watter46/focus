<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatter;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatted;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\FormatterInterface;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Splitted;


final class CommentFormatter implements FormatterInterface
{
    /**
     * コメントを変換
     *
     * @param  string    $content
     * @param  Formatted $formatted
     * @param  int       $index
     * @param  Splitted  $splitted
     * @return Formatted
     */
    public function format(string $content, Formatted $formatted, int $index, Splitted $splitted): Formatted
    {
        if (!$splitted->canAddComment($index)) {
            return $formatted->add(collect(['comment' => $content]));
        };

        return $formatted->addComment($content);
    }
    
    /**
     * コメントフォーマットに対応しているか判定する
     *
     * @param  string $content
     * @return bool
     */
    public function supports(string $content): bool
    {
        if ($this->isCommand($content)) {
            return false;
        }
                
        if ($content === '') {
            return false;
        }
        
        return true;
    }
    
    /**
     * コマンドか判定する
     *
     * @param  string $content
     * @return bool
     */
    private function isCommand(string $content): bool
    {
        $pattern = "/^-\x20\[.\]\x20[^\x20]+/";

        return (bool) preg_match($pattern, $content);
    }
}