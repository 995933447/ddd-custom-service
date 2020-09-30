<?php
namespace App\Service\Command\DTO\Issue\ReplyIssueImageMessageCommand;

use App\Service\AbstractDTO;

class InputDTO extends AbstractDTO
{
    /**
     * @var int
     */
    protected $issueId;

    /**
     * @var string
     */
    protected $imageName;

    /**
     * @var string
     */
    protected $imagePath;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getImageFileType(): string
    {
        return $this->imageFileType;
    }

    /**
     * @param string $image_file_type
     */
    public function setImageFileType(string $image_file_type): void
    {
        $this->imageFileType = $image_file_type;
    }

    /**
     * @return int
     */
    public function getImageSize(): int
    {
        return $this->imageSize;
    }

    /**
     * @param int $image_size
     */
    public function setImageSize(int $image_size): void
    {
        $this->imageSize = $image_size;
    }

    /**
     * @return string
     */
    public function getImageName(): string
    {
        return $this->imageName;
    }

    /**
     * @param string $image_name
     */
    public function setImageName(string $image_name): void
    {
        $this->imageName = $image_name;
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     */
    public function setImagePath(string $image_path): void
    {
        $this->imagePath = $image_path;
    }

    /**
     * @return int
     */
    public function getIssueId(): int
    {
        return $this->issueId;
    }

    /**
     * @param int $issueId
     */
    public function setIssueId(int $issue_id): void
    {
        $this->issueId = $issue_id;
    }
}