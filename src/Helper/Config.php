<?php

namespace FireGento\MageBot\Helper;

use FireGento\MageBot\Botman\BotmanConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Mpociot\BotMan\BotMan;

/**
 * Class Config
 *
 * @package FireGento\MageBot\Helper
 */
class Config implements BotmanConfig
{
    /**
     * configurations keys
     */
    const CONFIG_PATH_HIPCHAT_URLS = 'magebot/hipchat/urls';

    /**
     * configurations keys nexmo
     */
    const CONFIG_PATH_NEXMO_KEY = 'magebot/nexmo/key';
    const CONFIG_PATH_NEXMO_SECRET = 'magebot/nexmo/secret';

    /**
     * configurations keys microsoft
     */
    const CONFIG_PATH_MICROSOFT_BOT_HANDLE = 'magebot/microsoft/bot_handle';
    const CONFIG_PATH_MICROSOFT_ADD_ID = 'magebot/microsoft/app_id';
    const CONFIG_PATH_MICROSOFT_ADD_KEY = 'magebot/microsoft/app_key';

    /**
     * configurations keys slack real time api
     */
    const CONFIG_PATH_SLACK_REAL_TIME_API_TOKEN = 'magebot/slack_real_time_api/token';

    /**
     * configurations keys telegram
     */
    const CONFIG_PATH_TELEGRAM_API_TOKEN = 'magebot/telegram/token';

    /**
     * configurations keys facebook
     */
    const CONFIG_PATH_FACEBOOK_TOKEN = 'magebot/facebook/token';
    const CONFIG_PATH_FACEBOOK_APP_SECRET = 'magebot/facebook/app_secret';
    const CONFIG_PATH_FACEBOOK_WEBHOOK_TOKEN = 'magebot/facebook/webhook_token';

    /**
     * configurations keys wechat
     */
    const CONFIG_PATH_WECHAT_APP_ID = 'magebot/wechat/app_id';
    const CONFIG_PATH_WECHAT_APP_KEY = 'magebot/wechat/app_key';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Returns the configured value for the config value magebot/hipchat/urls
     *
     * @return array
     */
    public function getMageBotHipChatUrls()
    {
        $config = $this->scopeConfig->getValue(Config::CONFIG_PATH_HIPCHAT_URLS, ScopeInterface::SCOPE_STORE);
        return explode(",", $config);
    }

    /**
     * Returns the configured value for the config value magebot/nexmo/key
     *
     * @return string
     */
    public function getMageBotNexmoKey()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_NEXMO_KEY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/nexmo/secret
     *
     * @return string
     */
    public function getMageBotNexmoSecret()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_NEXMO_SECRET, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/nexmo/secret
     *
     * @return string | null
     */
    public function getMageBotMicrosoftBotHandle()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_MICROSOFT_BOT_HANDLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/microsoft/app_id
     *
     * @return string | null
     */
    public function getMageBotMicrosoftAppId()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_MICROSOFT_ADD_ID, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/microsoft/app_key
     *
     * @return string | null
     */
    public function getMageBotMicrosoftAppKey()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_MICROSOFT_ADD_KEY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/slack_real_time_api/token
     *
     * @return string | null
     */
    public function getMageBotSlackToken()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_SLACK_REAL_TIME_API_TOKEN, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/slack_real_time_api/token
     *
     * @return string | null
     */
    public function getMageBotTelegramToken()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_TELEGRAM_API_TOKEN, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/facebook/app_secret
     *
     * @return string | null
     */
    public function getMageBotFacebookToken()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_FACEBOOK_TOKEN, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/facebook/app_secret
     *
     * @return string | null
     */
    public function getMageBotFacebookAppSecret()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_FACEBOOK_APP_SECRET, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/facebook/webhook_token
     *
     * @return string | null
     */
    private function getMageBotFacebookWebhookToken()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_FACEBOOK_WEBHOOK_TOKEN, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/facebook/app_secret
     *
     * @return string | null
     */
    public function getMageBotWechatAppId()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_WECHAT_APP_ID, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns the configured value for the config value magebot/facebook/app_secret
     *
     * @return string | null
     */
    public function getMageBotWechatAppKey()
    {
        return $this->scopeConfig->getValue(Config::CONFIG_PATH_WECHAT_APP_KEY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $config = [
            'hipchat_urls' => $this->getMageBotHipChatUrls(),
            'nexmo_key' =>  $this->getMageBotNexmoKey(),
            'nexmo_secret' => $this->getMageBotNexmoSecret(),
            'microsoft_bot_handle' => $this->getMageBotMicrosoftBotHandle(),
            'microsoft_app_id' => $this->getMageBotMicrosoftAppId(),
            'microsoft_app_key' => $this->getMageBotMicrosoftAppKey(),
            'slack_token' => $this->getMageBotSlackToken(),
            'telegram_token' => $this->getMageBotTelegramToken(),
            'facebook_token' => $this->getMageBotFacebookToken(),
            'facebook_app_secret' => $this->getMageBotFacebookAppSecret(),
            'wechat_app_id' => $this->getMageBotWechatAppId(),
            'wechat_app_key' => $this->getMageBotWechatAppKey()
        ];
    }

    /**
     * @param BotMan $botman
     */
    public function configureBotMan(BotMan $botman)
    {
        if($this->getMageBotFacebookWebhookToken()) {
            $botman->verifyServices($this->getMageBotFacebookWebhookToken());
        }
    }
}
