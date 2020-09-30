<?php
/**
 * Firebase cloud message push service.
 */
namespace Infrastructure\MessageSender\MessagePusher;

use Google_Client;
use Google_Service_Drive;
use Infrastructure\Shared\Cache\AbstractCacheRepository;
use Infrastructure\Shared\Log\AbstractLogger;

class FirebaseCloudMessage
{
    protected $project_id = [
        'android' => 'happera-46619',
        'ios' => 'innovate-cab5c'
    ];
    protected $url = 'https://fcm.googleapis.com/v1/projects/%s/messages:send'; // firebase推送消息的地址

    protected $logger;

    protected $cacheRepository;

    protected $authConfigBasePath;

    public function __construct(AbstractLogger $logger, AbstractCacheRepository $cache_repository, string $auth_config_base_path)
    {
        $this->logger = $logger;
        $this->cacheRepository = $cache_repository;
        $this->authConfigBasePath = rtrim($auth_config_base_path, '/');
    }

    public function getAccessToken($os)
    {
        $access_token = $this->cacheRepository->get($os . '_access_token');

        if ($access_token) {
            return $access_token;
        } else {
            $client = new Google_Client();
            $client->useApplicationDefaultCredentials();
            $client->setAuthConfig("{$this->authConfigBasePath}/service-account-file-{$os}.json");
            $client->addScope(Google_Service_Drive::DRIVE);
            $client->setScopes(['https://www.googleapis.com/auth/firebase.messaging']);     # 授予访问 FCM 的权限

            $token_info = $client->fetchAccessTokenWithAssertion();

            if ($token_info['access_token']) {

                $this->cacheRepository->put($os . '_access_token', $token_info['access_token'], 3000);
                return $token_info['access_token'];

            } else {

                $this->logger->info('获取access_token失败', ['token_info' => $token_info]);
                return false;
            }
        }
    }

    public function firebaseCloudPushMessage($game_id, $uid, $os, $title, $content)
    {
        $key = $uid . '_' . $game_id . '_' . $os;
        
        if (!$uid || !$game_id || !$os) {

            $this->logger->info('缺少參數', ['uid' => $uid, 'game_id' => $game_id, 'os' => $os]);
            return false;

        }

        $token = $this->cacheRepository->get($key);

        if (!$token) {
            $this->logger->info('不存在该用户令牌', ['key' => $key]);
            return false;
        }

        $access_token = $this->getAccessToken($os);

        if (!$access_token) {
            $this->logger->info('获取access_token失败');
            return false;
        }
        $post_arr = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'body' => $content,
                    'title' => $title,
                ],
                'data' => [
                    'uid' => '' . $uid . '',
                ],
            ],
        ];

        $post_arr = json_encode($post_arr);

        $header = [
            'Content-Type: application/json;',
            'Authorization: Bearer ' . $access_token
        ];
        $project_id = $this->project_id[$os];

        $this->url = sprintf($this->url, $project_id);

        $option['header'] = $header;

        $result = custom_curl('POST', $this->url, $post_arr, $option);

        $this->logger->info('推送消息返回值', ['result' => $result]);

        return true;
    }

}