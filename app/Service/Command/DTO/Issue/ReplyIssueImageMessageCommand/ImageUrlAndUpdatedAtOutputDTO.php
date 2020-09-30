<?php
namespace App\Service\Command\DTO\Issue\ReplyIssueImageMessageCommand;

use App\Service\AbstractDTO;

class ImageUrlAndUpdatedAtOutputDTO extends AbstractDTO
{
    /**
     * @var string
     */
    protected $imageUrl;

    /**
     * @var string
     */
    protected $addtime;

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return string
     */
    public function getAddtime(): string
    {
        return $this->addtime;
    }

    /**
     * @param string $addtime
     */
    public function setAddtime(string $addtime): void
    {
        $this->addtime = $addtime;
    }
}