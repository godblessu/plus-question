# 排行

- [获取解答排行](#获取解答排行)
- [获取问答达人排行](#获取问答达人排行)

## 获取解答排行

根据一定时间内发布的回答数进行的排序

```
GET /user/question-answer/ranks/answers
```

## 传入参数

| 名称 | 类型 | 必填 | 说明 |
|:----:|:-----|:----:|------|
| limit | int | -    | 数据返回条数 默认10条 |
| type | string | -  | 筛选类型 `day` - 日排行 `week` - 周排行  `month` - 月排行 |
| offset | int | -   | 偏移量 默认为0 |

## 响应

```
Http Status 200 Ok
```

```json5
{
    "user_count": 0,
    "ranks": [
        {
            "id": 3,
            "name": "root2",
            "count": 7,
            "rank": 1,
            "following": false,
            "follower": false,
            "avatar": null,
            "bg": null,
            "verified": null
        },
        {
            "id": 2,
            "name": "root1",
            "count": 1,
            "rank": 2,
            "following": false,
            "follower": false,
            "avatar": null,
            "bg": null,
            "verified": null
        }
    ]
}
```

### 返回参数
| 名称 | 类型 | 说明 |
|:----:|:-----|------|
| user_count | int | 当前用户收到的点赞数 |
| ranks | array | 排行信息 |
| ranks.id | int | 用户id |
| ranks.name | string | 用户名称 |
| ranks.count | int | 用户发布的回答数 |
| ranks.rank | int | 用户排名 |
| ranks.following | bool | 对方用户是否关注了当前用户 |
| ranks.follower | bool | 对方用户是否被当前用户关注 |
| ranks.avatar | string/null | 用户头像 |
| ranks.bg | string/null | 用户背景图片 |
| ranks.verified | array/null | 用户认证资料 |

## 获取问答达人排行

根据全站回答收到的点赞数进行的排序

```
GET /user/question-answer/ranks/likes
```

## 传入参数

| 名称 | 类型 | 必填 | 说明 |
|:----:|:-----|:----:|------|
| limit | int | -    | 数据返回条数 默认10条 |
| offset | int | -   | 偏移量 默认为0 |

## 响应

```
Http Status 200 Ok
```

```json5
{
    "user_count": 0,
    "ranks": [
        {
            "id": 3,
            "name": "root2",
            "count": 7,
            "rank": 1,
            "following": false,
            "follower": false,
            "avatar": null,
            "bg": null,
            "verified": null
        },
        {
            "id": 2,
            "name": "root1",
            "count": 1,
            "rank": 2,
            "following": false,
            "follower": false,
            "avatar": null,
            "bg": null,
            "verified": null
        }
    ]
}
```

### 返回参数
| 名称 | 类型 | 说明 |
|:----:|:-----|------|
| user_count | int | 当前用户收到的点赞数 |
| ranks | array | 排行信息 |
| ranks.id | int | 用户id |
| ranks.name | string | 用户名称 |
| ranks.count | int | 用户的回答收到的点赞数 |
| ranks.rank | int | 用户排名 |
| ranks.following | bool | 对方用户是否关注了当前用户 |
| ranks.follower | bool | 对方用户是否被当前用户关注 |
| ranks.avatar | string/null | 用户头像 |
| ranks.bg | string/null | 用户背景图片 |
| ranks.verified | array/null | 用户认证资料 |