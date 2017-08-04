<?php

return [
    'notifications' => [
        'invited' => '你邀请:user已回答了你的问题',
        'answer' => '你的问题被:user回答',
    ],
    'charges' => [
        'invited' => [
            'subject' => '回答问答问题邀请获得悬赏',
            'body' => '回答问题《:body》',
        ],
    ],
    'reward' => [
        'attributes' => [
            'amount' => '金额',
        ],
        'required' => '请输入打赏:attribute',
        'min' => '你输入的:attribute非法',
        'max' => '余额不足',
        'not-user' => '打赏对象不存在，无法进行搭讪',
        'send-reward' => '打赏问答答案。',
        'get-reward' => '问答回答被打赏',
        'own' => '调皮，自己不可以给自己打赏',
    ],
];
