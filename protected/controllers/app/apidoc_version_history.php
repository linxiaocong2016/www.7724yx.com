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
 * @apiParam {String} data aes加密的第三方资料 <br>
 * data生成流程 <br>
 * 1. 客户端用随机aes密钥加密包含如下参数的json对象。<br>
 * type 注册类，0：qq，1：微博，2：微信 <br>
 * openid 授权openid <br>
 * nickname 授权昵称 <br>
 * img 授权头像 <br>
 * unionid 授权微信unionid（微信的时候必填，其他时留空）<br>
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

/**
 * @api {get} /app/v2/hezi/oauth 第三方登录
 * @apiName oauth
 * @apiDescription
 * 第三方登录
 * @apiVersion 2.0.2
 * @apiGroup User
 * 
 * @apiParam {String} key 客户端经过rsa加密的aes密钥
 * @apiParam {String} data aes加密的第三方资料 <br>
 * data生成流程 <br>
 * 1. 客户端用随机aes密钥加密包含如下参数的json对象。<br>
 * type 注册类，0：qq，1：微博，2：微信 <br>
 * openid 授权openid <br>
 * nickname 授权昵称 <br>
 * img 授权头像 <br>
 * unionid 授权微信unionid（微信的时候必填，其他时留空）<br>
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
 *          "token" : ""
 *          "sign" : "signsign",
 *			"headimg":"http://avatar.com/1"
 *       }
 *     }
 */

