# 收藏

- [收藏一个回答](#收藏一个回答)
- [取消收藏一个回答](#取消收藏一个回答)

## 收藏一个回答

```
POST /api/v2/question-answers/:answer/collections
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

## 取消收藏一个回答

```
DELETE /api/v2/question-answers/:answer/collections
```

### 响应

Header 204 No Content
