<?php
namespace Infrastructure\Persistence\Converter\Domain\Model\Issue\Eloquent;

use Infrastructure\Persistence\Converter\DomainEntityToDOConverterInterface;
use Domain\Model\Issue\Issue as IssueEntity;
use Infrastructure\Persistence\DataObject\Eloquent\Issue as IssueEloquentDO;

class IssueEntityToDOConverter implements DomainEntityToDOConverterInterface
{
    protected $issueEntity;

    public function __construct(IssueEntity $issueEntity)
    {
        $this->issueEntity = $issueEntity;
    }

    public function toDataObject(): IssueEloquentDO
    {
        $issue_do = new IssueEloquentDO();
        if (!is_null($issue_id = $this->issueEntity->getId())) {
            $issue_do->issue_id = $issue_id;
        }

        $issue_do->updatetime = $this->issueEntity->getUpdatedAt()->format('Y-m-d H:i:s');

        if ($this->issueEntity->isNotDeal()) {
            $issue_do->service_response = IssueEloquentDO::ISSUE_NOT_RESPONSE;
        } else if ($this->issueEntity->isDealing()) {
            $issue_do->service_response = IssueEloquentDO::ISSUE_HAS_RESPONSE;
        } else if ($this->issueEntity->isFinish()) {
            $issue_do->service_response = IssueEloquentDO::ISSUE_HAS_RESPONSE;
            $issue_do->work_deal = IssueEloquentDO::WORK_HAS_DEAL_STATUS;
        }

        $issue_do->is_star = $this->issueEntity->isFollowed()? IssueEloquentDO::IS_FOLLOWED:
            IssueEloquentDO::IS_NOT_FOLLOWED;

        $issue_do->is_deal = $this->issueEntity->isOpened()? IssueEloquentDO::ISSUE_OPENED_DEAL_STATUS:
            IssueEloquentDO::ISSUE_CLOSED_DEAL_STATUS;

        $issue_do->ip = $this->issueEntity->getCreatedAtIp();

        $issue_do->type_id =  $this->issueEntity->getIssueCategoryId();

        $issue_do->score = $this->issueEntity->getServiceScore();

        $issue_do->addtime = $this->issueEntity->getCreatedAt();

        $issue_do->title = $this->issueEntity->getIssueDetail()->getTitle();

        $issue_do->describe = $this->issueEntity->getIssueDetail()->getDescribe();

        $issue_do->server_id = $this->issueEntity->getIssueDetail()->getServerId();

        $issue_do->sdk_version_code = $this->issueEntity->getIssueDetail()->getSdkVersion();

        $issue_do->app_version_code = $this->issueEntity->getIssueDetail()->getGameVersion();

        $issue_do->model = $this->issueEntity->getIssueDetail()->getMachine()->getModel();

        $issue_do->devicebrand = $this->issueEntity->getIssueDetail()->getMachine()->getBrand();

        if ($this->issueEntity->getIssueDetail()->getMachine()->getOs()->isAndroidOs()) {
            $issue_do->os = IssueEloquentDO::ANDROID_MACHINE_OS;
        } else if ($this->issueEntity->getIssueDetail()->getMachine()->getOs()->isIosOS()) {
            $issue_do->os = IssueEloquentDO::IOS_MACHINE_OS;
        } else if ($this->issueEntity->getIssueDetail()->getMachine()->getOs()->isPcOS()) {
            $issue_do->os = IssueEloquentDO::PC_MACHINE_OS;
        } else if ($this->issueEntity->getIssueDetail()->getMachine()->getOs()->isWapOs()) {
            $issue_do->os = IssueEloquentDO::WAP_MACHINE_OS;
        }

        $issue_do->systemversion = $this->issueEntity->getIssueDetail()->getMachine()->getOs()->getVersion();

        if ($this->issueEntity->getIssueDetail()->getMachine()->getNetwork()->isCMCCNetwork()) {
            $issue_do->mnos = IssueEloquentDO::CMCC_MACHINE_NETWORK;
        } else if ($this->issueEntity->getIssueDetail()->getMachine()->getNetwork()->isKDDINetwork()) {
            $issue_do->mnos = IssueEloquentDO::KDDI_MACHINE_NETWORK;
        } else if ($this->issueEntity->getIssueDetail()->getMachine()->getNetwork()->isUNICOMNetwork()) {
            $issue_do->mnos = IssueEloquentDO::UNICOM_MACHINE_NETWORK;
        } else if ($this->issueEntity->getIssueDetail()->getMachine()->getNetwork()->isWifiNetwork()) {
            $issue_do->mnos = IssueEloquentDO::WIFI_MACHINE_NETWORK;
        }

        $issue_do->role_id = $this->issueEntity->getIssueDetail()->getGameRoleId();
        $issue_do->role_name =  $this->issueEntity->getIssueDetail()->getGameRoleName();
        $issue_do->game_id = $this->issueEntity->getIssueDetail()->getGameId();

        return $issue_do;
    }
}