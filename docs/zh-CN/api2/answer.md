# 问答 - 回答

- [回答一个提问](#回答一个提问)

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
