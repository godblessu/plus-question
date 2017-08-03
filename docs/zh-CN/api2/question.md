# 问题

- [发布问题](#发布问题)
- [更新问题](#更新问题)
- [获取问题列表](#获取问题列表)

## 发布问题

```
POST /questions
```

#### 输入

| 字段 | 类型 | 描述 |
|:----:|:----:|----|
| subject | 字符串 | **必须**，问题主题或者说标题，不能超过 255 **字节** ，必须以 `？` 结尾。（不区分全角或者半角） |
| topics | 数组 | **必须**，绑定的话题，数组子节点必须符合 `{ "id": 1 }` 的格式。 |
| body | 字符串 | 问题描述。 |
| anonymity | 枚举：`0` 或者 `1` | 作者是风匿名发布。 |
| amount | 数字 | 问题价值，悬赏金额 |
| look | 枚举：`0` 或者 `1` | 是否开启围观，当问题有采纳或者邀请人已回答，则对外部观众自动开启围观。设置围观必须设置悬赏金额。 |
| invitations | 数组 | 邀请回答，问题邀请回答的人，数组子节点必须符合 `{ "user": 1 }` 的格式，切不能存在自己。 |
| automaticity | 枚举：`0` 或者 `1` | 邀请悬赏自动入账，只邀请一个人的情况下，允许悬赏金额自动入账到被邀请回答者钱包中。 |

#### 响应

```
Status: 201 Created
```
```json
{
    "message": [
        "操作成功"
    ],
    "question": {
        "subject": "再测试一个问题?",
        "body": null,
        "anonymity": 1,
        "amount": 0,
        "automaticity": 0,
        "look": 0,
        "user_id": 1,
        "updated_at": "2017-08-01 06:06:37",
        "created_at": "2017-08-01 06:06:37",
        "id": 2
    }
}
```

## 更新问题

```
PATCH /questions/:question
```

#### 输入

| 字段 | 类型 | 描述 |
|:----:|:----:|----|
| subject | 字符串 | **当 `body` 不存在时，`subject` 为必须**，问题主题或者说标题，不能超过 255 **字节** ，必须以 `？` 结尾。（不区分全角或者半角） |
| body | 字符串 | **当 `subject` 不存在时，`body` 为必须**，问题描述。 |

#### 响应

```
Status: 204 No Content
```

## 获取问题列表

获取所有问题列表

```
GET /questions
```

获取某个话题下的问题列表

```
GET /question-topics/:topic/questions
```

#### 参数

| 名称 | 类型 | 描述 |
| type | 枚举：`all`、`new`、`hot`、`reward`、`excellent` | 默认值 `new`, `all` - 全部、`new` - 最新、`hot` - 热门、`reward` - 悬赏、`excellent` - 精选 。 |
| limit | Integer | 默认 `20` ，获取列表条数，修正值 `1` - `30`。 |
| offset | integer | 默认 `0` ，数据偏移量，传递之前通过接口获取的总数。 |

#### 响应

```
Status: 200 OK
```
```json
[
    {
        "id": 2,
        "user_id": 0,
        "subject": "再测试一个问题?",
        "body": null,
        "anonymity": 1,
        "amount": 0,
        "automaticity": 0,
        "look": 0,
        "excellent": 0,
        "status": 0,
        "comments_count": 0,
        "answers_count": 0,
        "watchers_count": 0,
        "likes_count": 0,
        "view_count": 0,
        "created_at": "2017-08-01 06:06:37",
        "updated_at": "2017-08-01 06:06:37"
    },
    {
        "id": 1,
        "user_id": 1,
        "subject": "第一个提问?",
        "body": null,
        "anonymity": 0,
        "amount": 0,
        "automaticity": 0,
        "look": 0,
        "excellent": 0,
        "status": 0,
        "comments_count": 0,
        "answers_count": 3,
        "watchers_count": 0,
        "likes_count": 0,
        "view_count": 0,
        "created_at": "2017-07-28 08:38:54",
        "updated_at": "2017-08-01 06:03:21",
        "user": {
            "id": 1,
            "name": "Seven",
            "bio": "Seven 的个人传记",
            "sex": 2,
            "location": "成都 中国",
            "created_at": "2017-06-02 08:43:54",
            "updated_at": "2017-07-25 03:59:39",
            "avatar": "http://plus.io/api/v2/users/1/avatar",
            "bg": "http://plus.io/storage/user-bg/000/000/000/01.png",
            "verified": null,
            "extra": {
                "user_id": 1,
                "likes_count": 0,
                "comments_count": 8,
                "followers_count": 0,
                "followings_count": 1,
                "updated_at": "2017-08-01 06:06:37",
                "feeds_count": 0,
                "questions_count": 5,
                "answers_count": 3
            }
        }
    }
]
```

| 字段 | 描述 |
| id | 问题唯一 ID 。 |
| user_id | 发布的用户 ID，如果是 `anonymity` 是 `1` 则该字段为 `0`。 |
| subject | 问题标题。 |
| body | 问题详情，markdown，如果没有详情为 `null`。 |
| anonymity | 是否匿名，`1` 代表匿名发布，匿名后不会返回任何用户信息。 |
| amount | 问题价值，悬赏金额，`0` 代表非悬赏。 |
| automaticity | 是否自动入账。客户端无用，邀请回答后端判断逻辑使用。 |
| look | 是否开启了围观。 |
| excellent | 是否属于精选问题。 |
| status | 问题状态，0 - 未解决，1 - 已解决， 2 - 问题关闭 。 |
| comments_count | 问题评论总数统计。 |
| answers_count | 问题答案数量统计。 |
| watchers_count | 问题关注的人总数统计。 |
| likes_count | 喜欢问题的人总数统计。 |
| view_count | 问题查看数量统计。 |
| created_at | 问题创建时间。 |
| updated_at | 问题修改时间。 |
| user | 用户资料，如果是 `anonymity` 是 `1` 则该字段不存在。 |
