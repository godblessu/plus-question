# 问答 - 回答

- [获取回答列表](#获取回答列表)
- [回答一个提问](#回答一个提问)

## 获取回答列表

```
GET /questions/:question/answers
```

#### 参数

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 默认 `20` ，获取列表条数，修正值 `1` - `30`。 |
| offset | integer | 默认 `0` ，数据偏移量，传递之前通过接口获取的总数。 |
| order_type | String | 默认 `default`, `default` - 默认排序（按照点赞数）、 `time` - 按照发布时间倒序。 |

#### 响应

```
Status: 200 OK
```
```json
[
    {
        "id": 1,
        "question_id": 1,
        "user_id": 1,
        "body": "笑嘻嘻，我是回答。",
        "anonymity": 0,
        "adoption": 0,
        "invited": 0,
        "comments_count": 0,
        "rewards_amount": 0,
        "rewarder_count": 0,
        "likes_count": 0,
        "created_at": "2017-08-01 03:40:54",
        "updated_at": "2017-08-01 03:40:54",
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
|:----:|----|
| id | 回答唯一标识 ID 。 |
| question_id | 回答所属问题标识 ID 。 |
| user_id | 发布回答用户标识ID，如果 `anonymity` 为 `1` 则只为 `0` 。 |
| body | 回答的内容，markdown 。 |
| anonymity | 是否是匿名回答 。 |
| adoption | 是否是采纳答案。 |
| invited | 是否该回答是被邀请的人的回答。 |
| comments_count | 评论总数统计。 |
| rewards_amount | 回答打赏总额统计。 |
| rewarder_count | 打赏的人总数统计。 |
| likes_count | 回答喜欢总数统计。 |
| created_at | 回答创建时间。 |
| updated_at | 回答更新时间。 |
| user | 回答的用户资料，参考「用户」文档，如果 `anonymity` 为 `1` 则不存在这个字段或者为 `null` 。 |

## 回答一个提问

```
POST /questions/:question/answers
```

#### 输入

| 名称 | 类型 | 描述 |
|:----:|:----|----|
| body | String | **必须**，回答的内容，markdown。 |
| anonymity | Enum: `0` , `1` | 是否匿名。 |

#### 响应

```
Status: 201 Created
```
```json
{
    "message": [
        "操作成功"
    ],
    "answer": {
        "question_id": 1,
        "user_id": 1,
        "body": "哈哈，可以的。",
        "anonymity": 1,
        "invited": false,
        "updated_at": "2017-08-01 06:03:21",
        "created_at": "2017-08-01 06:03:21",
        "id": 3
    }
}
```
