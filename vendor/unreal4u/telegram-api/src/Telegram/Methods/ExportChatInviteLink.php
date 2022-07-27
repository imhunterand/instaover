<?php

declare(strict_types = 1);

namespace unreal4u\TelegramAPI\Telegram\Methods;

use Psr\Log\LoggerInterface;
use unreal4u\TelegramAPI\Abstracts\TelegramMethods;
use unreal4u\TelegramAPI\Abstracts\TelegramTypes;
use unreal4u\TelegramAPI\InternalFunctionality\TelegramResponse;
use unreal4u\TelegramAPI\Telegram\Types\Custom\ResultString;

/**
 * Use this method to generate a new invite link for a chat; any previously generated link is revoked. The bot must be
 * an administrator in the chat for this to work and must have the appropriate admin rights. Returns the new invite link
 * as String on success
 *
 * Note: Each administrator in a chat generates their own invite links. Bots can't use invite links generated by other
 * administrators. If you want your bot to work with invite links, it will need to generate its own link using
 * exportChatInviteLink — after this the link will become available to the bot via the getChat method. If your bot needs
 * to generate a new invite link replacing its previous one, use exportChatInviteLink again
 *
 * Objects defined as-is June 2020
 *
 * @see https://core.telegram.org/bots/api#exportchatinvitelink
 */
class ExportChatInviteLink extends TelegramMethods
{
    /**
     * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
     * @var string
     */
    public $chat_id = '';

    public static function bindToObject(TelegramResponse $data, LoggerInterface $logger): TelegramTypes
    {
        return new ResultString($data->getResultString(), $logger);
    }

    public function getMandatoryFields(): array
    {
        return [
            'chat_id',
        ];
    }
}