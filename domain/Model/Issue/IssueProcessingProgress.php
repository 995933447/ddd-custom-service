<?php
namespace Domain\Model\Issue;

use Domain\AbstractValueObject;

class IssueProcessingProgress extends AbstractValueObject
{
    const NOT_DEAL_STAGE = 0;

    const DEALING_STAGE = 1;

    const FINISH_DEAL_STAGE = 2;

    protected $stage;

    protected function __construct(int $stage)
    {
        $this->stage = $stage;
    }

    public static function beNotDeal(): self
    {
        return new static(static::NOT_DEAL_STAGE);
    }

    public static function beDealing(): self
    {
        return new static(static::DEALING_STAGE);
    }

    public static function beFinish(): self
    {
        return new static(static::FINISH_DEAL_STAGE);
    }

    public function equalsTo(self $issue_processing_progress)
    {
        return $this->stage === $issue_processing_progress->stage;
    }
}