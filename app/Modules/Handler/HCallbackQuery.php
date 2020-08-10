<?php

namespace App\Modules\Handler;

use App\Modules\BotTelegram\BotTelegram;
use App\Modules\BotTelegram\BTKeyboards;
use App\Modules\BotTelegram\BTMessages;
use App\Modules\Items\Text;

class HCallbackQuery
{
    /**
     * set text (not used yet)
     *
     * @var \App\Modules\Items\Text $text
     */
    private $text;

    /**
     * set BotTelegram
     *
     * @var \App\Modules\BotTelegram\BotTelegram
     */
    private $botTelegram;

    public function __construct()
    {
        $this->text = new Text();
        $this->botTelegram = new BotTelegram();


    }

    /**
     * olah data callbackQuery
     *
     * @param array $result
     * @return void
     */
    public function handle(array $result)
    {
        /**
         * @var string $callbackID
         */
        $callbackID = $result['callback_query']['id'];

        /**
         * @var string $chatID
         */
        $chatID = $result['callback_query']['message']['chat']['id'];

        /**
         * @var string $messageID
         */
        $messageID = $result['callback_query']['message']['message_id'];

        /**
         * @var string $data
         */
        $data = strtoupper($result['callback_query']['data']);

        /**
         * @var string $fName
         */
        $fName = $result['callback_query']['message']['chat']['first_name'];

        /**
         * salah satu cara cek $data
         * bisa mengunakan fungsi strpos() bila berisi data yang rumit
         */
        switch ($data) {
            case 'BACK':

                /**
                 * siapkan text yang akan dikirim bot
                 */
                $text = Text::welcome();

                /**
                 * siapkan keyboard yang akan dikirim bot
                 */
                $replyMarkup = BTKeyboards::replyKeyboardMarkup();

                /**
                 * @var array $data
                 */
                $data = [
                    'chatID' => $chatID,
                    'text' => $text,
                    'replyMarkup' => $replyMarkup,
                ];

                /**
                 * @var array $data2
                 */
                $data2 = [
                    'callbackID' => $callbackID,
                    'text' => 'Back!'
                ];

                /**
                 * isi data pada setiap parameter yang dibutuhkan
                 */
                $sendMessage = BTMessages::textMessage($data);

                /**
                 * siapkan data parameter untuk annswer callback query
                 */
                $answerCallback = BTMessages::answerCallbackQuery($data2);

                /**
                 * kirimkan balasan callback query
                 */
                $this->botTelegram->answerCallbackQuery($answerCallback);

                /**
                 * bot membalas pesan ke user
                 */
                $this->botTelegram->sendMessage($sendMessage);
                break;

            case 'EDITTEXT':

                $text = Text::editText();
                $replyMarkup = BTKeyboards::inlineKeyboardEditText();

                $data = [
                    'chatID' => $chatID,
                    'messageID' => $messageID,
                    'text' => $text['text1'],
                    'replyMarkup' => $replyMarkup,

                ];

                $data2 = [
                    'callbackID' => $callbackID,
                    'text' => 'Edited!'
                ];

                $answerCallback = BTMessages::answerCallbackQuery($data2);
                $editText = BTMessages::editMessageText($data);

                $this->botTelegram->answerCallbackQuery($answerCallback);
                $this->botTelegram->editMessageText($editText);

                break;

            case '' :
                break;

            default:
                # code...
                break;
        }

    }

}
