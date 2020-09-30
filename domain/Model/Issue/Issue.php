<?php
namespace Domain\Model\Issue;

use Domain\AbstractAggregateRoot;

class Issue extends AbstractAggregateRoot
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $customerName;

    /**
     * @var string
     */
    protected $createdAtIp;

    /**
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * @var \DateTimeImmutable
     */
    protected $updatedAt;

    /**
     * @var IssueDetail
     */
    protected $issueDetail;

    /**
     * @var bool
     */
    protected $isFollowed;

    /**
     * @var int
     */
    protected $serviceScore;

    /**
     * @var IssueProcessingProgress
     */
    protected $processingProgress;

    /**
     * @var IssueStatus
     */
    protected $issueStatus;

    /**
     * @var int
     */
    protected $issueCategoryId;

    public function __construct(
        int $id,
        string $customer_name,
        string $created_at_ip,
        \DateTimeImmutable $created_at,
        \DateTimeImmutable $updated_at,
        IssueDetail $issue_detail,
        bool $is_followed,
        int $serviceScore,
        IssueProcessingProgress $processing_progress,
        IssueStatus $issue_status,
        int $issue_category_id
    )
    {
        $this->setId($id);
        $this->setCustomerName($customer_name);
        $this->setCreatedAtIp($created_at_ip);
        $this->createdAt = $created_at;
        $this->updatedAt = $updated_at;
        $this->issueDetail = $issue_detail;
        $this->isFollowed = $is_followed;
        $this->serviceScore = $serviceScore;
        $this->processingProgress = $processing_progress;
        $this->issueStatus = $issue_status;
        $this->issueCategoryId = $issue_category_id;
    }

    public function setId(?int $id)
    {
        if (is_int($id) && $id <= 0) {
            throw new \InvalidArgumentException();
        }

        $this->id = $id;
    }

    protected function setCustomerName(string $customer_name)
    {
        if (empty($customer_name)) {
            throw new \InvalidArgumentException();
        }

        $this->customerName = $customer_name;
    }

    protected function setCreatedAtIp(string $ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException();
        }

        $this->createdAtIp = $ip;
    }

    protected function setIssueCategoryId(int $issue_category_id)
    {
        if ($issue_category_id <= 0) {
            throw new \InvalidArgumentException();
        }

        $this->issueCategoryId = $issue_category_id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCustomerName(): int
    {
        return $this->customerName;
    }

    /**
     * @return string
     */
    public function getCreatedAtIp(): string
    {
        return $this->createdAtIp;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return IssueDetail
     */
    public function getIssueDetail(): IssueDetail
    {
        return $this->issueDetail;
    }

    /**
     * @return bool
     */
    public function isFollowed(): bool
    {
        return $this->isFollowed;
    }

    /**
     * @return int
     */
    public function getServiceScore(): int
    {
        return $this->serviceScore;
    }

    /**
     * @return IssueProcessingProgress
     */
    public function getProcessingProgress(): IssueProcessingProgress
    {
        return $this->processingProgress;
    }


    public function updateProcessProgress(IssueProcessingProgress $processing_progress)
    {
        $this->resetUpdatedAt();
        $this->processingProgress = $processing_progress;
    }

    public function isNotDeal(): bool
    {
        return $this->processingProgress->equalsTo(IssueProcessingProgress::beNotDeal());
    }

    public function isDealing(): bool
    {
        return $this->processingProgress->equalsTo(IssueProcessingProgress::beDealing());
    }

    public function isFinish(): bool
    {
        return $this->processingProgress->equalsTo(IssueProcessingProgress::beFinish());
    }

    public function isOpened(): bool
    {
        return $this->getIssueStatus()->equalsTo(IssueStatus::beOpened());
    }

    public function isClosed(): bool
    {
        return $this->getIssueStatus()->equalsTo(IssueStatus::beClosed());
    }

    public function mayReply(): bool
    {
        return !is_null($this->getId()) && $this->isOpened() && !$this->isFinish();
    }

    /**
     * @return IssueStatus
     */
    public function getIssueStatus(): IssueStatus
    {
        return $this->issueStatus;
    }

    /**
     * @return int
     */
    public function getIssueCategoryId(): int
    {
        return $this->issueCategoryId;
    }

    public function follow()
    {
        $this->resetUpdatedAt();
        $this->isFollowed = true;
    }

    public function unfollow()
    {
        $this->resetUpdatedAt();
        $this->isFollowed = false;
    }

    protected function resetUpdatedAt()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}