<?php
namespace Infrastructure\Persistence\Converter\Domain\Model\Issue\Eloquent;

use Domain\Model\Issue\IssueDetail;
use Domain\Model\Issue\IssueProcessingProgress;
use Domain\Model\Issue\IssueStatus;
use Domain\Model\Issue\Machine;
use Domain\Model\Issue\MachineNetwork;
use Domain\Model\Issue\MachineOs;
use Infrastructure\Persistence\Converter\DOToDomainEntityConverterInterface;
use Infrastructure\Persistence\DataObject\Eloquent\Issue as IssueEloquentDO;
use Domain\Model\Issue\Issue as IssueEntity;

class IssueDOToEntityConverter implements DOToDomainEntityConverterInterface
{
    protected $issueEloquentDO;

    public function __construct(IssueEloquentDO $issue_do)
    {
        $this->issueEloquentDO = $issue_do;
    }

    public function toEntity(): IssueEntity
    {
        $commit_issue_machine_os_vo = null;

        switch ($this->issueEloquentDO->os) {
            case IssueEloquentDO::PC_MACHINE_OS:
                $commit_issue_machine_os_vo = MachineOs::bePcOs($this->issueEloquentDO->systemversion);
                break;
            case IssueEloquentDO::IOS_MACHINE_OS:
                $commit_issue_machine_os_vo = MachineOs::beAndroidOs($this->issueEloquentDO->systemversion);
                break;
            case IssueEloquentDO::ANDROID_MACHINE_OS:
                $commit_issue_machine_os_vo = MachineOs::beAndroidOs($this->issueEloquentDO->systemversion);
                break;
            case IssueEloquentDO::WAP_MACHINE_OS:
                $commit_issue_machine_os_vo = MachineOs::beWapOs($this->issueEloquentDO->systemversion);
                break;
            default:
                throw new \InvalidArgumentException('Not support machine os.');
        }

        $commit_issue_network_vo = null;

        switch ($this->issueEloquentDO->mnos) {
            case IssueEloquentDO::KDDI_MACHINE_NETWORK:
                $commit_issue_network_vo = MachineNetwork::beKDDI();
                break;
            case IssueEloquentDO::CMCC_MACHINE_NETWORK:
                $commit_issue_network_vo = MachineNetwork::beCMCC();
                break;
            case IssueEloquentDO::UNICOM_MACHINE_NETWORK:
                $commit_issue_network_vo = MachineNetwork::beUNICOM();
                break;
            case IssueEloquentDO::WIFI_MACHINE_NETWORK:
                $commit_issue_network_vo = MachineNetwork::beWifi();
                break;
        }

        $issue_processing_progress_vo = null;

        if ($this->issueEloquentDO->work_deal == IssueEloquentDO::WORK_HAS_DEAL_STATUS) {
            $issue_processing_progress_vo = IssueProcessingProgress::beFinish();
        } else if ($this->issueEloquentDO->service_response == IssueEloquentDO::ISSUE_HAS_RESPONSE) {
            $issue_processing_progress_vo = IssueProcessingProgress::beDealing();
        } else {
            $issue_processing_progress_vo = IssueProcessingProgress::beNotDeal();
        }

        $issue_status_vo = null;
        if ($this->issueEloquentDO->is_deal == IssueEloquentDO::ISSUE_OPENED_DEAL_STATUS) {
            $issue_status_vo = IssueStatus::beOpened();
        } else {
            $issue_status_vo = IssueStatus::beClosed();
        }

        return new IssueEntity(
            (int) $this->issueEloquentDO->issue_id,
            $this->issueEloquentDO->user_name,
            $this->issueEloquentDO->ip,
            new \DateTimeImmutable($this->issueEloquentDO->created_at),
            new \DateTimeImmutable($this->issueEloquentDO->updated_at),
            new IssueDetail(
                $this->issueEloquentDO->title,
                $this->issueEloquentDO->describe,
                $this->issueEloquentDO->server_id,
                $this->issueEloquentDO->role_name,
                $this->issueEloquentDO->role_id,
                $this->issueEloquentDO->game_id,
                new Machine(
                    $this->issueEloquentDO->model,
                    $commit_issue_machine_os_vo,
                    $this->issueEloquentDO->devicebrand,
                    $commit_issue_network_vo
                ),
                $this->issueEloquentDO->sdk_version_code,
                $this->issueEloquentDO->app_version_code
            ),
            $this->issueEloquentDO->is_star === IssueEloquentDO::IS_FOLLOWED? true: false,
            $this->issueEloquentDO->score,
            $issue_processing_progress_vo,
            $issue_status_vo,
            $this->issueEloquentDO->type_id
        );
    }
}