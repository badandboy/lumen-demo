# 说明

  * 该系统是店铺服务中心

## 环境

 * Lumen framework：5.5.\*

## 主要分层结构
 
 * Model：仅当成Eloquent class。
 * Repository：辅助model，处理数据库逻辑，然后注入到service。【目前这层不做强烈要求】
 * Service：辅助controller，处理业务逻辑，然后注入到controller。
 * Controller：接收HTTP请求，调用其他service。
 
  **流程 Controller->Service->Repository->Model**
 
  *示例参考 DemoController的demo方法*
 
## 要求
 * 代码需加注释，需加上作者和时间
 * 数据库操作需参考ORM
 * 缓存操作需要Cache::方式
 * 不同业务尽量在不同的控制逻辑中，要求尽量符合SOLID 原则
 * 所有的请求体校验在app/Requests/
 * 全局响应枚举统一在app/Enums/ResponseEnum/
 * 响应构造器在app/Http/Responses/ResponseBuilder.php
 * 全局请求日志统一在app/Http/Middleware/RequestLogMiddleware.php
 * 枚举类统一在app/Enums
 * 工具类统一在app/Utils,包括常量配置
 * 公共方法函数统一在app/Helpers/helpers.php
 * 数据传输对象统一在app/Dto/ （这里比较范些，不再细分vo,dto,do等）
 * 外部服务调用统一在app/Transport/CallServices/
 * 外部调用api配置统一在config/api.php
 
## 建议
 * 面向接口编程
 * SOLID 原则
 
## 示例
   * 可参考DemoController
   * Service层的使用参考[https://www.kancloud.cn/curder/laravel/408485](https://www.kancloud.cn/curder/laravel/408485)
   * Repository层的使用参考[https://www.kancloud.cn/curder/laravel/408484](https://www.kancloud.cn/curder/laravel/408484)
   
##
test
