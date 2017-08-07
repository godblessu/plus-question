# 点赞

- [点赞一个回答](#点赞一个回答)
- [取消点赞一个回答](#取消点赞一个回答)
- [一个回答的点赞列表](#一个回答的点赞列表)

## 点赞一个回答

```
POST /api/v2/question-answers/:answer/likes
```

### 响应

Header 201 Created

```json5
{
    "message": [
        "操作成功"
    ]
}
```

## 取消点赞一个回答

```
DELETE /api/v2/question-answers/:answer/likes
```

### 响应

Header 204 No Content

## 一个回答的点赞列表

```
GET /api/v2/question-answers/:answer/likes
```

### 参数

| 名称  | 说明 |
|:-----:|------|
| limit | 数据返回条数 默认20 |
| after | 数据翻页标识 该倒序列表中，标识为列表数据最小id|

### 响应

Header 200 OK

```json5
[
    {
        "id": 4,
        "user_id": 1,
        "target_user": 1,
        "likeable_id": 1,
        "likeable_type": "question-answers",
        "created_at": "2017-08-07 06:29:40",
        "updated_at": "2017-08-07 06:29:40"
    }
]
```
