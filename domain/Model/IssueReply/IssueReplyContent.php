<?php
namespace Domain\Model\IssueReply;

use Domain\AbstractValueObject;

class IssueReplyContent extends AbstractValueObject
{
    const TEXT_TYPE = 0;
    const IMAGE_TYPE = 1;

    protected $type;

    protected $content;

    protected function __construct(int $type, $content)
    {
        $this->type = $type;
        $this->content = $content;
    }

    protected function setContent($content)
    {
        if (empty($content)) {
            throw new \InvalidArgumentException();
        }

        $this->content = $content;
    }

    public static function fromImage(string $path): self
    {
        return new static(static::IMAGE_TYPE, $path);
    }

    public static function formText(string $text): self
    {
        return new static(static::TEXT_TYPE, $text);
    }

    public function equalsTo(self $issue_replay_content): bool
    {
        return $this->type = $issue_replay_content->type && $this->content = $issue_replay_content->content;
    }

    public function isImage(): bool
    {
        return $this->type === static::IMAGE_TYPE;
    }

    public function isText(): bool
    {
        return $this->type === static::TEXT_TYPE;
    }

    public function __toString(): string
    {
        return (string) $this->content;
    }
}