<?php
//该页数据需要自己申请根据自己的填写
$wechat_config['app_id'] = "wxc9154e533b333d00";// 公众号身份标识

$wechat_config['app_secret'] = "7bb9ea4ddf3aec02670329df4dcc9ce5";// 权限获取所需密钥 Key

$wechat_config['pay_sign_key'] = "xmqqy0592wlkjyxgs6038527rjygrl18";// 加密密钥 Key，也即appKey

$wechat_config['partner_id'] = '1235277202';// 财付通商户身份标识

$wechat_config['partner_key'] = '1235277202';// 财付通商户权限密钥 Key

$wechat_config['notify_url'] = '';// 微信支付完成服务器通知页面地址

$wechat_config['cacert_url'] = dirname(__FILE__).'/1218891802_20140425185952.pfx';

$wechat_config['AccessTokenFile'] = dirname(__FILE__).'/token.txt';

?>