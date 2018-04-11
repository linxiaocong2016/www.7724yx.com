<?php
/**
 * api版本历史
 * @author zhoushen
 * @since 2016-06-01
 */

/**
 * @api {get} /app/v2/hezi/oauth 第三方登录
 * @apiName oauth
 * @apiDescription
 * 第三方登录
 * @apiVersion 2.0.1
 * @apiGroup User
 *
 * @apiParam {String} key 客户端经过rsa加密的aes密钥
 * @apiParam {String} data 客户端aes加密的请求参数, 加密格式：
 * 请求参数json编码后aes加密，请求参数包括如下字段:
 * 
 * type 注册类，0：qq，1：微博，2：微信
 * 
 * openid 授权openid
 * 
 * nickname 授权昵称
 * 
 * img 授权头像
 * 
 * unionid 授权微信unionid（微信的时候必填，其他时留空）
 * 
 * sex 0未知 1男 2女
 * 
 * @apiSuccess {Integer} code 1:成功，<0：失败
 * @apiSuccess {String} msg  返回接口提示信息
 * @apiSuccess {HashMap} data  返回接口数据
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "code": 1,
 *       "msg": "注册成功",
 *       "data" : {
 *          "uid" : '1'
 *          "username" : "zhoushen",
 *          "nickname" : "zhoushen",
 *          "img" : "http://avatar.com/1",
 *          "token" : "",
 *       }
 *     }
 */