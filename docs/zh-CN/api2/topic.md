# 话题 API

- [获取全部话题](#获取全部话题)

## 获取全部话题

```
GET /question-topics
```

#### 请求参数

| 名称 | 类型 | 描述 |
| limit | 数字 | 这次请求获取的条数，默认为 `20` 条，为了避免过大或者错误查询，设置了一个修正值，最大 `50` 最小 `1` 。 |
| after | 数字 | 获取 `id` 之后的数据，要获取某条话题之后的数据，传递该话题 ID。 |
| follow | 任意 | 是否检查当前用户是否关注了某话题，默认为不检查，如果传递 `follow` 切拥有任意值（空除外），都会检查当前用户与话题的关注关系。 |
| name | 字符串 | 用语搜索话题，传递话题名称关键词。 |

#### 响应

```
Status: 200 OK
```
```json
[
    {
        "id": 1,
        "name": "PHP",
        "description": "我是PHP",
        "questions_count": 0,
        "follows_count": 0,
        "has_follow": false,
        "avatar": null
    }
]
```

| 字段 | 描述 |
|:----:|----|
| id | 话题ID |
| name | 话题名称 |
| description | 话题描述 |
| questions_count | 话题下的问题数量统计 |
| follows_count | 话题下的关注用户统计 |
| avatar | 话题头像，如果存在则为「字符串」，否则固定值 `null` |
| has_follow | 当「请求参数」传递了 `follow` 才会出现 `has_follow` 字段，布尔值，`true` 表示当前用户关注了这个话题，`false` 反之。 |