<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Zhiyi\Plus\Contracts\FindMarkdownFileTrait;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use SlimKit\PlusQuestion\API2\Requests\QuestionAnswer as QuestionAnswerRequest;

class AnswerController extends Controller
{
    use FindMarkdownFileTrait;

    /**
     * Append answer to question.
     *
     * @param \SlimKit\PlusQuestion\API2\Requests\QuestionAnswer $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Answer $answer
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(QuestionAnswerRequest $request,
                          ResponseFactoryContract $response,
                          AnswerModel $answer,
                          QuestionModel $question)
    {
        $user = $this->resolveUser(
            $request->user()
        );

        $anonymity = $request->input('anonymity') ? 1 : 0;
        $body = $request->input('body');
        $images = $this->findMarkdownImageNotWithModels($body);

        $answer->question_id = $question->id;
        $answer->user_id = $user->id;
        $answer->body = $body;
        $answer->anonymity = $anonymity;
        $answer->invited = in_array($user->id, $question->invitations->toArray());

        // 查询已邀请回答的答案。
        $invitedAnswer = $question->answers()
            ->where('invited', 1)
            ->first();

        $question->getConnection()->transaction(function () use ($question, $answer, $images, $user, $invitedAnswer) {

            // Save Answer.
            $question->answers()->save($answer);

            // Count
            $question->increment('answers_count', 1);

            // Update images.
            $images->each(function ($image) use ($answer) {
                $image->channel = 'question-answers:images';
                $image->raw = $answer->id;
                $image->save();
            });

            // Automaticity ?
            if ($question->anonymity && $answer->invited && ! $invitedAnswer) {
                $user->wallet()->increment('balance', $question->amount);

                $charge = new WalletChargeModel();
                $charge->user_id = $user->id;
                $charge->channel = 'user';
                $charge->account = $question->id;
                $charge->action = 1;
                $charge->amount = $question->amount;
                $charge->subject = trans('plus-question::answers.charges.invited.subject');
                $charge->body = trans('plus-question::answers.charges.invited.body', ['body' => $question->subject]);
                $charge->status = 1;

                $user->walletCharges()->save($charge);
            }
        });

        $message = trans(
            ($answer->invited && ! $invitedAnswer)
                ? 'plus-question::answers.notifications.invited'
                : 'plus-question::answers.notifications.answer',
            ['user' => $user->name]
        );
        $question->user->sendNotifyMessage('question:answer', $message, [
            'question' => $question,
            'answer' => $answer,
            'user' => $user,
        ]);

        return $response->json(['message' => [trans('plus-question::messages.success')]], 201);
    }
}
