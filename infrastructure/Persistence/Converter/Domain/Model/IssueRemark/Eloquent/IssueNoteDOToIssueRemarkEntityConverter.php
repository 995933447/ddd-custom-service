<?php
namespace Infrastructure\Persistence\Converter\Domain\Model\IssueRemark\Eloquent;

use Infrastructure\Persistence\Converter\DOToDomainEntityConverterInterface;
use Domain\Model\IssueRemark\IssueRemark as IssueRemarkEntity;

class IssueNoteDOToIssueRemarkEntityConverter implements DOToDomainEntityConverterInterface
{
    public function toEntity(): IssueRemarkEntity
    {
    }
}