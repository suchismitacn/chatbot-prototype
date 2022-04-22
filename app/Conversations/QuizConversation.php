<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use GuzzleHttp\Client;

class QuizConversation extends Conversation
{
    protected $category;
    protected $level;
    protected $correct_answer;

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askCategory();
    }

    public function askCategory()
    {
        $options = [
            'code' => 'Programming',
            'sql' => 'SQL',
            'random' => 'Random',
            // 'test' => 'Test'
        ];

        $question = $this->createQuestion('Choose a category first.', $options, 'ask_category');

        return $this->ask($question, function (Answer $answer) use ($options) {
            if ($answer->isInteractiveMessageReply()) {
                $this->category = $answer->getValue();
                $this->askLevel();
            } else {
                $this->say('I didn\'t understand that. Please select from the above options.');
                $this->repeat();
            }
        });
    }

    public function askLevel()
    {
        $options = [
            'easy' => 'Beginner',
            'medium' => 'Intermediate',
            'hard' => 'Expert'
        ];

        $question = $this->createQuestion('Choose a difficulty level.', $options, 'ask_level');

        return $this->ask($question, function (Answer $answer) use ($options) {
            if ($answer->isInteractiveMessageReply()) {
                $this->level = $answer->getValue();
                $this->getQuiz();
            } else {
                $this->say('I didn\'t understand that. Please select from the following options.');
                $this->repeat();
            }
        });
    }

    protected function askQuiz($quiz)
    {
        $options = (array) $quiz->answers;
        $this->correct_answer = $options[$quiz->correct_answer];

        $question = $this->createQuestion($quiz->question, $options, 'get_quiz');

        return $this->ask($question, function (Answer $answer) use ($quiz) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === $quiz->correct_answer) {
                    $reply = 'Good job!';
                } else {
                    $reply = 'Oops! The correct answer is: ' . $this->correct_answer;
                }
                $this->say($reply);
            } else {
                $this->say('I didn\'t understand that. Please select from the following options.');
                $this->repeat();
            }
        });
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

    protected function getQuiz()
    {
        try {
            $client = new Client(['headers' => ['X-Api-Key' => config('app.quiz_api_key')]]);
            $params = [
                'difficulty' => $this->level,
                'limit' => 1
            ];
            if ($this->category != 'random') {
                $params['category'] = $this->category;
            }
            $request = $client->get(config('app.quiz_api_uri'), ['query' => $params]);
            $response = json_decode($request->getBody()->getContents());
            $quiz = array_first($response);
            if ($quiz) $this->askQuiz($quiz);
        } catch (\Exception $exception) {
            \Log::debug("Exception: ".$exception->getMessage());
            $this->say('Something went wrong! :(');
        }
    }
}
