### Used

- **[`Php 8`](https://www.php.net/releases/8.0/ru.php#:~:text=PHP%208.0%20%E2%80%94%20%D0%B1%D0%BE%D0%BB%D1%8C%D1%88%D0%BE%D0%B5%20%D0%BE%D0%B1%D0%BD%D0%BE%D0%B2%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5%20%D1%8F%D0%B7%D1%8B%D0%BA%D0%B0,%D1%82%D0%B8%D0%BF%D0%BE%D0%B2%2C%20%D0%BE%D0%B1%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%BA%D0%B5%20%D0%BE%D1%88%D0%B8%D0%B1%D0%BE%D0%BA%20%D0%B8%20%D0%BA%D0%BE%D0%BD%D1%81%D0%B8%D1%81%D1%82%D0%B5%D0%BD%D1%82%D0%BD%D0%BE%D1%81%D1%82%D0%B8.)**
- **[`Laravel 9`](https://laravel.com/)**
- **[`MySQL`](https://www.mysql.com/)**

# In Project

> *CRUD* [ **C**reate **R**ead **U**pdate **D**elete ]

<a href="https://documenter.getpostman.com/view/9990014/UVCCdiSN" target="_blank"><img src="https://github.com/TursunboyevJahongir/click-test-tesk/blob/master/public/postman.svg" align="right" width="50">

### Global postman documentation [](https://documenter.getpostman.com/view/9990014/UVCCdiSN)

[//]: # (> <a href="public/kesh_app.postman_collection.json" download>Postman Collection</a>)
## Possible params for `get` method

###### params are not required

* `list_type`\->string\[pagination,collection\] default pagination
* `columns`\->array default all columns
* `relations`\->array default null
* `limit`\->integer default 30
    *   **working with colection**
* `per_page`\->integer default 30
    *   **working with pagination**
* `is_active`\->boolean\[or 0,1\] default all
    *   or `filters[][is_active]=0` \[0,1\]
* `pluck`->string | array default null
  * working with collection
  * if string need send column name
    * if array pluck[column] required
         * pluck[key] optional default null
* `order`\->string default id
* `sort`\-> string\[asc,desc\] default desc
* `search`\->string default null
* `filters`\->array default null
    *   array accessive key=column value=searching text
    *   **{{host}}/admin/users?filters\[0\]\[first_name\]=Owner&filters\[0\]\[last_name\]=Of**
* `not_filters`\->**not_filters** reverse **filters**
* `or_filters`->**or_filters** from request, if any of them are equal it will work
* `only_deleted`\->boolean\[0,1\] default 0(*false*)


* * *

## `Headers`

*   `Accept-Language`:ru \[ru,uz,en\] default ru
*   `Accept`:application/json `required`


* * *

## `Response`

```
{
    "code": 200,
    "message": "OK",
    "data": {
         //data
    }
}

```

#### ~~Error~~ `Response`

```
{
    "code": 422,
    "message": "The given data was invalid.",
    "data": {
        "errors": {
             //Errors
        }
     }
}

```

*   `code`\->**int** \[200,201,204,401,403,404,422,500\]
*   `message`\->**string** default ''
    *   *the language of the message will be changed by the header Accept-Language*
*   `data`\->**array|Collection** default \[\]

<details><summary><b style="color:#355C7D;font-size:20px">User</b></summary>

```
- Profile RU
- User CRUD --- set role
- Category CRUD
- Role CRUD //todo
- Product CRUD //todo
```

</details>

