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
* `columns`\->string default all columns. *separated from each other by a comma(,)*
    * **host.com/products?columns=id,name,description,...** *add more with* `,`
* `relations`\->string default null
    * multi relations=parent;author;... *add more with* `;`
    * And you can multi relationship with dot notation relations=products.mainImage;author;...
* `limit`\->integer default 30
    * **working with colection**
* `per_page`\->integer default 30
    * **working with pagination**
* `conditions`\->string|array default null
    * the conditions are **and** with each other
    * string condition=column:value;column2:value2
    * array condition[column]=value&column[column2]=value2
      ```http request 
      https://host.com/users?conditions[first_name]=Jahongir&conditions[last_name]=Jahongir&conditions[author.middle_name]=Jahongir
      ```
      or you can use this on one variable
      ```http request 
      https://host.com/users?condition=first_name:Jahongir;last_name:Tursunboyev;author.middle_name=doe
      ```
* `not_conditions`\-> array default null
    * **conditions and with each other. and the result will be reversed**
    * **not_conditions** reverse **conditions**
* `or_conditions`-> array default null
    * the conditions are **or** with each other
    * **or_conditions** from request, if any of them are equal it will work
* `between`\->array default null
    * array accessive key=column value=int or date between FROMtoTO,if only **FROM** is given, it is taken from
      **FROM** to the end. if only **TO**(*toTO*) is given, it takes from start to **TO** . if **between**(*FROMtoTO*)
      is given, it takes all the data inside **FROMtoTO**
    * **{{host}}/users?between[price]=100to200&between[created_at]=2022-11-10&between[amount\]=to200**
* `not_between`\->**not_between** reverse **between**
* `is_active`\->boolean\[or 0,1\] default all
    * or `conditions[is_active]=0` \[0,1\]\
* `search`\->string default null
* `searchFilds`->string array default null.
    * string
    ```http request
    https://host.com/users?search=John&searchFilds=first_name,last_name,author.first_name
    ```
   * array
    ```http request
    https://host.com/users?search=John&searchFields[]=first_name&searchFields[]=last_name&searchFields[]=author.first_name
    ```
* `orderBy`\->string default id
* `sortBy`\-> string\[asc,desc\] default desc
* `appends`->string default null
    * working with collection
    * multi appends=full_name;appends2;...  *add more with* `;`
* `pluck`->string default null
  * The pluck method retrieves all of the values for a given key:
  * https://host.com/products?list_type=collection
    ```json
          {
            "products": [
                    {
                        "id": 150,
                        "category_id": 5,
                        "name": "suscipit"
                    },
                    {
                        "id": 149,
                        "category_id": 11,
                        "name": "aspernatur"
                    },
                    {
                        "id": 148,
                        "category_id": 5,
                        "name": "occaecati"
                    }
            ]
          }
    ```
  * https://host.com/products?list_type=collection&pluck=name
      ```json
          {
            "products": [
                "suscipit",
                "aspernatur",
                "occaecati",
                "iure"
            ]
          }
      ```
  * working with collection
  * The pluck method also supports retrieving nested values using "dot" notation
  * https://host.com/products?relations=category.author&list_type=collection&pluck=id:category.author.first_name
    ```json
        {
          "products": [
              "150": "Isaias",
              "149": "Rubye",
              "148": "Rubye",
              "147": "Urban",
              "146": "Caden",
              "145": "Alexandre",
              "144": "Super",
              "143": "Emerald",
              "142": "Jamison"
          ]
        }
    ```
    * note : object keys unique
* `only_deleted`\->boolean\[0,1\] default 0(*false*)

Sort, OrderBy

*host.com/directory?orderBy=column*

`https://host.com/products?orderBy=name&sortBy=ASC`

```json
[
    {
        "id": 3,
        "category_id": 3,
        "name": "Apple"
    },
    {
        "id": 1,
        "name": "Grape"
    },
    {
        "id": 2,
        "name": "Kiwi"
    }
]
```

Sorting through relation's column *only works with belongsTo relation.Does not work multi-depth dot notation.Only works
with one dot(one relation)*

*host.com/directory?orderBy=relation.column*

`https://host.com/products?orderBy=category.name&sortedBy=desc`

Query will have something like this

```sql
...
LEFT JOIN categories ON products.category_id = categories.id
...
ORDER BY categories.name
...
```

Multi orderBy

*host.com/directory?orderBy=column;relation.column;relation2.column2...*

`https://host.com/products?orderBy=name;category.name&sortedBy=desc`

```php
Product::leftJoin("categories", "products.category_id", "categories.id")->orderBy('name','desc')->orderBy('categories.name','desc');
...
Model::leftJoin("relationTable", "selfTable.foreignKey", "relationTable.ownerKey")->orderBy('column')->orderBy('relationTable.column')...;

```

* * *

## `Headers`

* `Accept-Language`:ru \[ru,uz,en\] default ru
* `Accept`:application/json `required`

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

* `code`\->**int** \[200,201,204,401,403,404,422,500\]
* `message`\->**string** default ''
    * *the language of the message will be changed by the header Accept-Language*
* `data`\->**array|Collection** default \[\]

<details><summary><b style="color:#355C7D;font-size:20px">Example</b></summary>

```
- Profile RU
- User CRUD --- set role
- Category CRUD
- Role CRUD //todo
- Product CRUD //todo
```

</details>

