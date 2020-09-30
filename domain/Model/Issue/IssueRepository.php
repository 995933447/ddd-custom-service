<?php
namespace Domain\Model\Issue;

interface IssueRepository
{
    public function findById(int $id): ?Issue;

    public function findAndLockById(int $id): ?Issue;

    public function save(Issue $issue): void;

    public function exitById(int $id): bool;

    public function getByIds(array $ids): array;
}