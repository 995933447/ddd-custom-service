<?php
namespace Infrastructure\Persistence\Converter\Domain\Model\Issue\Eloquent;

use Infrastructure\Persistence\Converter\DomainEntityToDOConverterInterface;
use Infrastructure\Persistence\DataObject\Eloquent\Issue as IssueEloquentDO;
use Domain\Model\Issue\Issue as IssueEntity;

class DirtyFromCompareIssueEntitySnapshotToDOConverter implements DomainEntityToDOConverterInterface
{
    protected $referenceIssueEntity;

    protected $snapIssueEntity;

    public function __construct(IssueEntity $reference_issue_entity, IssueEntity $snapshot_issue_entity)
    {
        $this->referenceIssueEntity = $reference_issue_entity;
        $this->snapIssueEntity = $snapshot_issue_entity;
    }

    public function toDataObject(): IssueEloquentDO
    {
        $this->checkMayTransfer();

        if (!$this->isDirty()) {
            return $this->makeNotDirtyDo();
        }

        return $this->compareDirtyToDO();
    }

    protected function compareDirtyToDO(): IssueEloquentDO
    {
        $issue_do = new IssueEloquentDO();
        if (!is_null($issue_id = $this->referenceIssueEntity->getId())) {
            $issue_do->issue_id = $issue_id;
        }

        $issue_do->updatetime = $this->referenceIssueEntity->getUpdatedAt()->format('Y-m-d H:i:s');

        if (!$this->snapIssueEntity->getProcessingProgress()->equalsTo($this->referenceIssueEntity->getProcessingProgress())) {
            if ($this->referenceIssueEntity->isNotDeal()) {
                $issue_do->service_response = IssueEloquentDO::ISSUE_NOT_RESPONSE;
            } else if ($this->referenceIssueEntity->isDealing()) {
                $issue_do->service_response = IssueEloquentDO::ISSUE_HAS_RESPONSE;
            } else if ($this->referenceIssueEntity->isFinish()) {
                $issue_do->service_response = IssueEloquentDO::ISSUE_HAS_RESPONSE;
                $issue_do->work_deal = IssueEloquentDO::WORK_HAS_DEAL_STATUS;
            }
        }

        if ($this->referenceIssueEntity->isFollowed() !== $this->snapIssueEntity->isFollowed()) {
            $issue_do->is_star = $this->referenceIssueEntity->isFollowed()? IssueEloquentDO::IS_FOLLOWED:
                IssueEloquentDO::IS_NOT_FOLLOWED;
        }

        if (!$this->referenceIssueEntity->getIssueStatus()->equalsTo($this->snapIssueEntity->getIssueStatus())) {
            $issue_do->is_deal = $this->referenceIssueEntity->isOpened()? IssueEloquentDO::ISSUE_OPENED_DEAL_STATUS:
                IssueEloquentDO::ISSUE_CLOSED_DEAL_STATUS;
        }

        if ($this->referenceIssueEntity->getCreatedAtIp() !== $this->snapIssueEntity->getCreatedAtIp()) {
            $issue_do->ip = $this->referenceIssueEntity->getCreatedAtIp();
        }

        if ($this->referenceIssueEntity->getIssueCategoryId() !== $this->snapIssueEntity->getIssueCategoryId()) {
            $issue_do->type_id =  $this->referenceIssueEntity->getIssueCategoryId();
        }

        if ($this->referenceIssueEntity->getServiceScore() !== $this->snapIssueEntity->getServiceScore()) {
            $issue_do->score = $this->referenceIssueEntity->getServiceScore();
        }

        if ($this->referenceIssueEntity->getCreatedAt() !== $this->snapIssueEntity->getCreatedAt()) {
            $issue_do->addtime = $this->referenceIssueEntity->getCreatedAt()->format('Y-m-d H:i:s');
        }

        if (
            !($reference_issue_detail = $this->referenceIssueEntity->getIssueDetail())
            ->equalsTo($snapshot_issue_detail = $this->snapIssueEntity->getIssueDetail())
        ) {
            if ($reference_issue_detail->getTitle() !== $snapshot_issue_detail->getTitle()) {
                $issue_do->title = $reference_issue_detail->getTitle();
            }

            if ($reference_issue_detail->getDescribe() !== $snapshot_issue_detail->getDescribe()) {
                $issue_do->describe = $reference_issue_detail->getDescribe();
            }

            if ($reference_issue_detail->getServerId() !== $snapshot_issue_detail->getServerId()) {
                $issue_do->server_id = $reference_issue_detail->getServerId();
            }

            if ($reference_issue_detail->getSdkVersion() !== $snapshot_issue_detail->getSdkVersion()) {
                $issue_do->sdk_version_code = $reference_issue_detail->getSdkVersion();
            }

            if ($reference_issue_detail->getGameVersion() !== $snapshot_issue_detail->getGameVersion()) {
                $issue_do->app_version_code = $reference_issue_detail->getGameVersion();
            }

            if (
                !($commit_reference_issue_machine = $reference_issue_detail->getMachine())
                    ->equalsTo($commit_snapshot_issue_machine = $snapshot_issue_detail->getMachine())
            ) {
                if ($commit_reference_issue_machine->getModel() !== $commit_snapshot_issue_machine->getModel()) {
                    $issue_do->model = $commit_reference_issue_machine->getModel();
                }

                if ($commit_reference_issue_machine->getBrand() !== $commit_snapshot_issue_machine->getBrand()) {
                    $issue_do->devicebrand = $commit_reference_issue_machine->getBrand();
                }

                if (!$commit_reference_issue_machine->getOs()->equalsTo($commit_snapshot_issue_machine->getOs())) {
                    if ($commit_reference_issue_machine->getOs()->isAndroidOs()) {
                        $issue_do->os = IssueEloquentDO::ANDROID_MACHINE_OS;
                    } else if ($commit_reference_issue_machine->getOs()->isIosOS()) {
                        $issue_do->os = IssueEloquentDO::IOS_MACHINE_OS;
                    } else if ($commit_reference_issue_machine->getOs()->isPcOS()) {
                        $issue_do->os = IssueEloquentDO::PC_MACHINE_OS;
                    } else if ($commit_reference_issue_machine->getOs()->isWapOs()) {
                        $issue_do->os = IssueEloquentDO::WAP_MACHINE_OS;
                    }

                    $issue_do->systemversion = $commit_reference_issue_machine->getOs()->getVersion();
                }

                if (
                    !$commit_reference_issue_machine->getNetwork()
                        ->equalsTo($commit_snapshot_issue_machine->getNetwork()))
                {
                    if ($commit_reference_issue_machine->getNetwork()->isCMCCNetwork()) {
                        $issue_do->mnos = IssueEloquentDO::CMCC_MACHINE_NETWORK;
                    } else if ($commit_reference_issue_machine->getNetwork()->isKDDINetwork()) {
                        $issue_do->mnos = IssueEloquentDO::KDDI_MACHINE_NETWORK;
                    } else if ($commit_reference_issue_machine->getNetwork()->isUNICOMNetwork()) {
                        $issue_do->mnos = IssueEloquentDO::UNICOM_MACHINE_NETWORK;
                    } else if ($commit_reference_issue_machine->getNetwork()->isWifiNetwork()) {
                        $issue_do->mnos = IssueEloquentDO::WIFI_MACHINE_NETWORK;
                    }
                }
            }

            if ($reference_issue_detail->getGameRoleId() !== $snapshot_issue_detail->getGameRoleId()) {
                $issue_do->role_id = $reference_issue_detail->getGameRoleId();
                $issue_do->role_name = $reference_issue_detail->getGameRoleName();
            }

            if ($reference_issue_detail->getGameId() !== $reference_issue_detail->getGameId()) {
                $issue_do->game_id = $reference_issue_detail->getGameId();
            }
        }

        return $issue_do;
    }

    protected function makeNotDirtyDo(): IssueEloquentDO
    {
        if (is_null($this->referenceIssueEntity->getId())) {
            $issue_do = new IssueEloquentDO();
        } else {
            $issue_do = IssueEloquentDO::where(IssueEloquentDO::ISSUE_ID_FIELD, $this->referenceIssueEntity->getId())->first();
        }

        return $issue_do;
    }

    protected function checkMayTransfer(): void
    {
        if ($this->referenceIssueEntity->getId() !== $this->snapIssueEntity->getId()) {
            throw new \RuntimeException("Snapshot entity and reference entity must has same id.");
        }
    }

    protected function isDirty(): bool
    {
        return $this->referenceIssueEntity->getCreatedAt()->getTimestamp()
            === $this->snapIssueEntity->getUpdatedAt()->getTimestamp();
    }
}