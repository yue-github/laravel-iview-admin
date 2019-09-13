 # 百用科技生成器文档
- 自动生成接口代码到项目目录下，可直接访问，访问地址：http://域名/表名/接口名（全部接口均为post请求）
- 自动建表（自动新增、删除表，新增、删除字段，自动修改字段属性）
- 自动生成接口文档：http://域名/api.html
- 自动生成数据字典：http://域名/data.html
### Xml配置文件：
```xml
<model>
    <entity name="users" time="true" label="用户管理">
        <attr name="name" type="name" label="用户名"/>
        <attr name="shop_id" type="key" table="shops" label="店铺id"/>
        <oper name="bind_user" type="login" label="登陆">
            data: {phone: phone, password: password}
        </oper>
	</entity>
</model>
```
```xml
---->model：根结点
-------->entity：数据表
  name： 数据表名
  time： true|false，默认false,是否生成created_at和updated_at时间戳字段
  label：注释
------------->attr：数据表字段
name：字段名
type：字段类型
table：外键对应的表
default：字段默认值
label：注释
------------->oper：接口
name：接口名
type：模板名
page：接口对应的页面，多个可用逗号隔开
label：注释
```
###attr标签type属性可选值：
```xml
type名称：mysql字段类型，长度
name: string, 100
key: bigInteger, null
phone: string, 11
idcard: string, 18
article: text, null
text: text, null
json: text, null
message: string, 500
file: string, 200
image: string, 200
video: string, 200
audio: string, 200
url: string, 200
address: string, 200
email: string, 200
int: integer, null
float: float, null
count: integer, null
money: decimal, 13
gender: enum, null
date: date, null
time: time, null
datetime: dateTime, null
state: tinyInteger, null
type: tinyInteger, null
password: string, 64
bool: boolean, null 
```

###常用代码模板使用方法：
**一、 search:** 最常用的搜索查询模板，支持左连接多重嵌套，支持对象成员为数组，支持whereNotNull,whereNotIn,whereIn,orWhere,groupBy,filter，子表加查询条件等（**调用此接口时offset和length必传**）
- **基本用法**
```xml
 <oper role="shop" page="用户管理" name="search" type="search" label="查询用户列表">
	data: {
        id: id,
        name: name,
        idcard: idcard
	},
    with: {
        name: name,
        phone: [like, phone]
    }
</oper> 
```
data：返回的字段
whth：查询条件，name: name代表精准查询，phone: [like, phone]代表模糊查询

------------
- **with的用法**
```xml
<oper role="shop" page="用户管理" name="search" type="search" label="查询用户列表">
    data: {
        id: id,
	    age: age,
        name: name,
        idcard: idcard
    },
    with: {age: [le, age]}
</oper>
```
**with：le**，查询age小于等于某值的用户
生成sql语句：
```sql
select id, age, name, idcard from users where age <= "前端传值"
```

------------
```xml
<oper role="shop" page="用户管理" name="search" type="search" label="查询用户列表">
      data: {
             id: id,
			 age: age,
             name: name,
             idcard: idcard
      },
      with: {age: [ge, age]}
</oper>
```
with：**ge**，查询age大于等于某值的用户
生成sql语句：
```sql
select id, age, name, idcard from users where age >= "前端传值"
```
------------
- **更多对应关系**
**lt**：小于，**gt**：大于，**ne**：不等于

------------


- **左连接1**
```xml
 <oper page="商品管理" name="search" type="search" label="查询产品">
    data: {
        id: id,
        name: name,
        shop_id: {
            name: shop_name
        }
    },
    with: {name: [like, name]}
</oper> 
```
shop_id为外键，shop_id后面大括号里配置的属性为shops表的字段，值为别名
生成的sql语句如下：
```sql
 SELECT
	products.id AS id,
	products. NAME AS NAME,
	shops. NAME AS shop_name
FROM
	products
LEFT JOIN shops ON products.shop_id = shops.id
WHERE
	products.NAME LIKE "%前端传%" 
```

------------
- **左连接2**
示例1：
```xml
 <oper page="商品管理" role="shop" name="search" type="search" label="查询产品">
    data: {
        id: id,
        name: name,
        product_labels.product_id: {
            label_id: label_id,
			product_id: product_id
        }
    },
    with: {name: [like, name]}
</oper> 
```
product_labels为产品-标签连接表
生成的sql语句如下：
```sql
SELECT
	products.id AS id,
	products. NAME AS NAME,
	product_labels.label_id AS label_id,
	product_labels.product_id AS product_id
FROM
	products
LEFT JOIN product_labels ON products.id = product_labels.product_id
WHERE
	products. NAME LIKE "%前端传%" 
```

------------
示例2(支持多重嵌套)：
```xml
 <oper role="client" page="班级管理" name="instructor_search" type="search" label="班级列表辅导员专用">
	data:{
		id: id,
		name: name,
		product_id: {
            id: product_id,
            project_id: {
                id: project_id,
				project_tasks.project_id: {
					task_id: {
						task_activities.task_id: {
							activity_id: {
								resources: resources
							}
						}
					}
				}
            }
        }
	}
</oper> 
```
生成的sql语句如下：
```sql
SELECT
	class.id AS id,
	class. NAME AS name,
	products.id as product_id,
	projects.id as project_id,
	activities.resources as resources
FROM
	class
LEFT JOIN products ON products.id = class.product_id
LEFT JOIN projects on products.project_id = projects.id
LEFT JOIN project_tasks on projects.id = project_tasks.project_id
LEFT JOIN tasks on project_tasks.task_id = tasks.id
LEFT JOIN task_activities on tasks.id = task_activities.task_id
LEFT JOIN activities on activities.id = task_activities.activity_id 
```
------------



#### 多种查询条件的使用：

```xml
 <oper page="商品管理" name="search" type="search" label="查询产品">
    data: {
        id: id,
        name: name
    },
    withNotIn: {id: id_arr}
</oper> 
```
**withNotIn**：查询id不在id_arr数组内的记录，id_arr为数组json序列化后的字符串

------------


```xml
 <oper page="商品管理" name="search" type="search" label="查询产品">
    data: {
        id: id,
        name: name
    },
	withIn: {id: id_arr}
</oper> 
```
**withIn**：查询id在id_arr数组内的的记录，id_arr为数组json序列化后的字符串

------------
```xml
 <oper page="商品管理" name="search" type="search" label="查询产品">
    data: {
        id: id,
        name: name，
		desc: desc
    },
	withNotNull : {desc: desc}
</oper> 
```
**withNotNull**：查询desc不为空的记录

------------
```xml
 <oper page="商品管理" name="search" type="search" label="查询产品">
    data: {
        id: id,
        name: name，
		price: price
    },
	orWith：{price: price, name: name}
</oper> 
```
**orWith**：（price == value || name == value）
生成sql语句：
```sql
select id, name, price from products where (price = "value" or name = "value")
```

------------


```xml
 <oper name="create" type="insert" label="查询产品">
    data: {
        id: id,
        name: name,
        desc: desc,
        image: image,
	    slabel: label
    },
	groupBy: {label: label}
</oper> 
```
**groupBy**：对查询的数据按照字段分组，去重

------------
```xml
 <oper name="get" type="first" label="产品详情">
    data: {
        id: id,
        name: name, 
        desc: desc, 
        image: image
    },
    filter: {onsale: 1}
</oper> 
```
**filter**：过滤，查询onsale字段为1的记录

------------
```xml
<oper name="get" type="first" label="产品详情">
    data: {
        id: id,
        name: name, 
        price: price
    },
    order: {price: desc}
</oper> 
```
**order:** 按指定字段对查询的记录排序，order的属性代表数据表字段名，值代表升序或降降序(asc,desc)

------------
- 对象成员为数组
```xml
<oper name="search" type="search" label="查询订单+订单下的多个商品">
    data: {
        id: id,
        order_no: order_no,
        address_id: address_id,
        items.purchased.order_id: {
            product_name: product_name,
            price: price
        }
    }
</oper>
```
**items.purchased.order_id**代表订单下有多个商品，使用**items**.前缀告诉生成器，这里应该生成一个数组
返回数据格式：
```json
[
    {
        "id": "id",
        "order_no": "order_no",
        "address_id": "address_id",
        "purchased": [
            {
                "product_name": "product_name",
                "price": "price"
            },
            {
                "product_name": "product_name",
                "price": "price"
            }
        ]
    },
    {
        "id": "id",
        "order_no": "order_no",
        "address_id": "address_id",
        "purchased": [
            {
                "product_name": "product_name",
                "price": "price"
            },
            {
                "product_name": "product_name",
                "price": "price"
            }
        ]
    }
]
```
**如果不使用items返回的数据结构：**
```json
[
    {
        "id": "id",
        "order_no": "order_no",
        "address_id": "address_id",
        "product_name": "product_name",
        "price": "price"
    },
    {
        "id": "id",
        "order_no": "order_no",
        "address_id": "address_id",
        "product_name": "product_name",
        "price": "price"
    },
    {
        "id": "id",
        "order_no": "order_no",
        "address_id": "address_id",
        "product_name": "product_name",
        "price": "price"
    },
    {
        "id": "id",
        "order_no": "order_no",
        "address_id": "address_id",
        "product_name": "product_name",
        "price": "price"
    }    
]
```

------------
**二、insert:** 插入一条数据
```xml
 <oper page="店铺标签管理" role="shop" name="create" type="insert" label="新增标签">
	data:{
		parent_id: parent_id,
		name: name,
        onsale: onsale
	}
</oper> 
```
data：代表要插入的数据，属性为数据表字段名，值为前端传输的字段名

------------
**三、union_insert:** 联合插入，同时向多个表插入数据，并且使用插入返回的自增id插入另一张表（使用场景：创建一条商品的同时向商品-标签关联表插入一条记录；创建商品的同时创建默认项目，任务，活动）
**示例1：**
```xml
 <oper page="商品管理" role="shop" name="create_product" type="union_insert" label="增加商品">
	data:{
            products.product_id: {
                name: name,
                desc: desc,
                image: image,
                price: price,
                is_project: CONSTANT.1
            },
            product_labels.product_label_id: {
                product_id: [product_id],
                label_id: label_id
            }
	}
</oper> 
```
示例配置向products表和product_labels表各插入了一条数据
**示例2：**
```xml
 <oper page="商品管理" role="shop" name="create" type="union_insert" label="增加产品自动关联项目，任务，标签">
    data: {
        projects.project_id: {
            name: name,
            shop_id: token.shop_id,
            desc: desc
        },
        products.product_id: {
            name: name,
            desc: desc,
            image: image,
            price: price,
            project_id: [project_id],
            period: period,
            teacher: teacher,
            is_project: CONSTANT.0
        },
        product_labels.product_labels_id: {
            product_id: [product_id],
            label_id: label_id
        },
        tasks.task_id: {
            name: name,
            shop_id: token.shop_id,
            desc: desc
        },
        project_tasks.project_task_id: {
            project_id: [project_id],
            task_id: [task_id]
        },
        task_activities.task_activities_id: {
            task_id: [task_id],
            activity_id: activity_id
        }
    }
</oper> 
```
配置方法：
```xml
	表名1.表名1_id：{
		数据库字段1：前端传传入字段1，
		数据库字段2：前端传传入字段2，
		数据库字段3：前端传传入字段3
	}，
	表名2.表名2_id：{
		数据库字段1：前端传传入字段1，
		数据库字段2：CONSTANT.0，
		数据库字段3：[表名1_id]
	}，
	表名3.表名3_id：{
		数据库字段1：前端传传入字段1，
		数据库字段2：前端传传入字段2，
		数据库字段3：[表名2_id]
	}，
```
表名_id 代表插入一条记录同步获取自增主键，可以插入到其他表中，使用[表名_id]获取
CONSTANT.value代表该字段默认插入value无需前端传值

------------
**四、update:** 更新数据
```xml
 <oper page="商品管理" role="shop" name="edit" type="update" label="更新商品">
    data:{
    	name: name,
        desc: desc,
        image: image,
        price: price,
    	project_id: project_id,
        period: period
    },
    with:{
    	id: id
    }
</oper> 
```
data：配置要更新的字段，属性名对应数据表字段名，属性值对应前端传值
with：更新条件,属性名对应数据表字段名，属性值对应前端传值

------------
**五、register:** 注册新用户
```xml
 <oper name="register" page="用户管理" type="register" label="新用户注册">
    data: {
        phone: phone,
        password: password,
        idcard: idcard,
        email: email,
        company: company,
        shop_id: shop_id
    }
</oper> 
```
data：配置注册新用户要记录的信息，只要字段名为password则使用md5加密

------------
**六、login:** 登陆
```xml
<oper name="login" page="用户管理" type="login" label="用户登录">
	data: {id: id},
	with: {phone: phone, password: password, shop_id: shop_id}
</oper>
```
data：配置返回的token携带的信息
with：配置登陆的验证条件，只要字段名为password则使用md5加密之后再跟数据库比对

------------
**七、setpwd:** 密码重置
```xml
 <oper role="client" page="用户管理" name="setpswd" type="setpwd" label="密码重置">
    data: {password: password},
    with: {id: token.id, password: opassword}
</oper>  
```
data：属性为对应数据表字段名，值为前端传的参数，自动加密password
with：修改密码验证条件token.id代表解密token之后的id属性值

------------
**八、delete:** 删除一条记录
```xml
<oper role="admin" name="delete" type="delete" label="删除标签">
	with:{id: id}
</oper> 
```
with: 配置删除条件

------------
**九：first:** 查询一条记录
配置方法与search模板一样，与search模板的区别在于只查一条记录，返回的是一个对象，不是数组，常用于获取用户信息，获取商品详情等
```xml
<oper role="client" name="profile" type="first" label="获取个人信息">
    data: {
        id: id,
        name: name, 
        idcard: idcard, 
        company: company, 
        phone: phone, 
        email: email, 
        balance: balance,
        isLookVideo : isLookVideo
    },
    with: {id: token.id}
</oper>
```

------------
**十、all:** 查询全部记录
配置方法与search模板一样，与search模板的区别在于该模板会查询全部记录，不需传offset和length，常用于获取标签等
```xml
<oper role="client" name="profile" type="all" label="获取标签">
    data: {
        id: id,
        name: name,
        parent_id: parent_id
    }
</oper>
```

------------
**十一、export_excel:** 导出excel表格 
```xml
<oper page="订单管理" name="export_excel" type="export_excel" label="导出订单excel">
    data: {
        state: "订单状态", 
        total: "订单总价", 
        pay_type: "支付类型"
    },
    with: {total: total}
</oper>
```
调用此接口需要前端传一个随机文件名file_name，接口返回成功后excel文件的下载地址为，http://域名/excel/file_name
**data:** 的属性名为数据表的字段名，值为字段的别名，支持中文
导出的表格如下：
订单状态|订单总价|支付类型
---|:--:|---:
内容|内容|内容
内容|内容|内容

------------











