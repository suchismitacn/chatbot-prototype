<?php

namespace App\Conversations\ChatBotV2;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class MenuConversation extends Conversation
{
    protected $department;
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askDepartment();
    }

    public function askDepartment()
    {
        $options = [
            'sales' => 'Sales',
            'service' => 'Service',
            'finance' => 'Finance',
            'valuation' => 'Valuation',
            'dealerships' => 'Nearest dealerships',
            'contacts' => 'Useful contacts',
            'live_chat' => 'Talk to an agent'
        ];
        $name = $this->bot->userStorage()->get('name') ?? 'user';
        $question = $this->createQuestion('Hello, ' . $name . '! What do you want to know about?', $options, 'ask_department');

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->bot->userStorage()->save([
                    'department' => $answer->getValue()
                ]);
                switch ($answer->getValue()) {
                    case 'dealerships':
                        $this->getNearestDealerships();
                        break;
                    case 'contacts':
                        $this->getContacts();
                        break;
                    case 'live_chat':
                        $this->talkToAgent();
                        break;
                    default:
                        $this->say('What ' . $answer->getValue() . ' related queries do you want help with?');
                }
            } else {
                $this->say('I didn\'t understand that. Please select from the following options.');
                $this->repeat();
            }
        });
    }

    protected function getNearestDealerships()
    {
        $this->ask('What is your postcode?', function (Answer $answer) {
            if (preg_match("/^[A-Za-z]{1,2}[0-9][0-9A-Za-z]?\s?[0-9][A-Za-z]{2}$/", $answer->getValue())) {
                /* Fetch nearest dealership info via api and display, I'm using placeholder values for demo purposes */
                $details = config('dealerships.' . $answer->getValue());
                if ($details) {
                    $response = '<p>Here are the dealership details for you:</p>
                    <p><strong>Address:</strong> ' . $details['address'] . '</p>
                    <p><strong>Sales:</strong> ' . $details['sales'] . '</p>
                    <p><strong>Service:</strong> ' . $details['service'] . '</p>';
                } else {
                    $response = 'Sorry! We couldn\'t find a dealership based on your postcode.';
                }
                $this->say($response);
            } else {
                $this->repeat('That doesn\'t look like a valid postcode. What is your postcode?');
            }
        });
    }

    protected function getContacts()
    {
        /* fetch contact details via api and display, I'm using placeholder values for demo purposes */
        $response = '<p>Here is how you can reach us:</p>
        <p>Stoneacre Head Office, Omega Boulevard, Thorne, DN8 5TX</p>
        <p><a href="https://www.facebook.com/StoneacreMotorGroup">Facebook</a></p>
        <p><a href="https://twitter.com/StoneacreMotors">Twitter</a></p>';
        $this->say($response);
    }

    protected function talkToAgent()
    {
        $chatUrl = route('live-chat', ['from' => 'user', 'chatId' => $this->bot->userStorage()->get('chatId')]);
        $this->say('Please go to this link: <a href=' . $chatUrl . ' target="_blank">Live Chat</a>');
    }

    protected function createButtons($options)
    {
        $buttons = [];
        foreach ($options as $key => $option) {
            if ($option) {
                $buttons[] = Button::create($option)->value($key);
            }
        }
        return $buttons;
    }

    protected function createQuestion(string $questionText, array $options, string $callbackId)
    {
        $buttons = $this->createButtons($options);

        return Question::create($questionText)
            ->fallback('Unable to ask question')
            ->callbackId($callbackId)
            ->addButtons($buttons);
    }
}
