<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use SlimKit\PlusQuestion\API2\Requests\AnswerReward as AnswerRewardRequest;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class AnswerRewardController extends Controller
{
    /**
     * Give a reward.
     *
     * @param \SlimKit\PlusQuestion\API2\Requests\AnswerReward $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Answer $answer
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(AnswerRewardRequest $request, ResponseFactoryContract $response, AnswerModel $answer)
    {
        $amount = $request->input('amount');
        $user = $request->user();
        $respondent = $answer->user;

        if (! $respondent) {
            return $response->json(['message' => [trans('plus-question::answers.reward.not-user')]], 422);
        }

        $userCharge = new WalletChargeModel();
        $userCharge->user_id = $user->id;
        $userCharge->channel = 'user';
        $userCharge->account = $respondent->id;
        $userCharge->action = 0;
        $userCharge->amount = $amount;
        $userCharge->subject = trans('plus-question::answers.reward.send-reward');
        $userCharge->body = $userCharge->subject;
        $userCharge->status = 1;

        $respondentCharge = new WalletChargeModel();
        $respondentCharge->user_id = $respondent->id;
        $respondentCharge->channel = 'user';
        $respondentCharge->account = $user->id;
        $respondentCharge->action = 1;
        $respondentCharge->amount = $amount;
        $respondentCharge->subject = trans('plus-question::answers.reward.get-reward');
        $respondentCharge->body = $respondentCharge->subject;
        $respondentCharge->status = 1;

        return $response->json($answer->getConnection()->transaction(function () use ($answer, $user, $respondent, $userCharge, $respondentCharge) {

            // increment reward for the answer.
            $answer->rewards_amount += $userCharge->amount;
            $answer->rewarder_count += 1;
            $answer->save();

            // save user charge.
            $userCharge->save();
            $respondentCharge->save();

            return ['message' => trans('plus-question::messages.success')];
        }))->setStatusCode(201);
    }
}
