<?php
namespace Domain\Model\IssueRemark;

interface IssueRemarkRepository
{
    public function save(IssueRemark $issue_remark): void;
}