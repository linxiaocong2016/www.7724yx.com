<!DOCTYPE html>
<!--[if lt IE 7]>      
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> 
<![endif]-->
<!--[if IE 7]>         
<html class="no-js lt-ie9 lt-ie8"> 
<![endif]-->
<!--[if IE 8]>        
<html class="no-js lt-ie9"> 
<![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> 
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo CHtml::encode($this->pageTitle['title']); ?></title>
<meta name="keywords" content="<?php echo CHtml::encode($this->pageTitle['keyword']); ?>"/>
<meta name="description" content="<?php echo CHtml::encode($this->pageTitle['descript']); ?>"/>
<meta name="google" value="notranslate">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!-- Le styles -->

<link rel="stylesheet" type="text/css" href="/assets/tech/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/assets/tech/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" type="text/css" href="/assets/tech/css/hx-20132.css" />
<link rel="stylesheet" type="text/css" href="/assets/tech/css/hx-2014.css" />
<link rel="stylesheet" type="text/css" href="/assets/tech/css/hx-2013-ie5.css" />
<script type="text/javascript" src="/assets/tech/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/assets/tech/js/jquery.cookie.js"></script>
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="/assets/tech/css/hx-2013-ie5.css" />
<![endif]-->
   <!-- 7724.com Baidu tongji analytics -->
<script>
var _hmt = _hmt || [];
(function() {
var hm = document.createElement("script");
hm.src = "//hm.baidu.com/hm.js?d44b67217b90bf2331d5c7cf55365d0a";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
    <div class="container-hx">
        <div class="top">
            <div class="top-box">
                <h1 class="logo">
                    <a href="#">
                        <img src="/assets/tech/img/top_logo1.png">
                    </a>
                </h1>
                <div class="login-box">
                </div>
            </div>
        </div>
        <?php 
       	 	$articleClass=CommonFunction::artClassInfo();
       	 	$cid_s=CommonFunction::nameToCid($_GET['cid_s']);
        ?>
        <div class="side-menu-hx">
            <div class="side-menu-top">
                <ul class="side-menu-list">
                    <li <?php if($cid_s==0) echo "class='active'"?>><a href="<?php echo $this->createurl('index/index')?>">首页</a></li>
                <?php foreach($articleClass as $v):?>
                	<?php if($v->id<=6):?>
                   <li <?php if($cid_s==$v->id) echo "class='active'"?>><a href="<?php echo $this->createurl('index/list',array("cid_s"=>$v->seo_tag));?>"><?php echo $v->name?></a></li>
                	<?php endif;?>
                <?php endforeach;?>
                </ul>
            </div>
            <div class="search">
                <form action="<?php echo $this->createurl('index/list')?>" method="get" class="form-search">
                    <input type="text" name="keyword_s" class="input-text" placeholder="搜索" value="">
                    <button class="search-btn"><i class="icon-search"></i></button>
                </form>
            </div>
        </div>
        <?php echo $content; ?>
    </div>
    <div id="append_parent">
    </div>
    <div id="ajaxwaitid">
    </div>
    <div id="fancybox-tmp">
    </div>
    <div id="fancybox-loading">
        <div>
        </div>
    </div>
    <div id="fancybox-overlay">
    </div>
    <div id="fancybox-wrap">
        <div id="fancybox-outer">
            <div class="fancybox-bg" id="fancybox-bg-n">
            </div>
            <div class="fancybox-bg" id="fancybox-bg-ne">
            </div>
            <div class="fancybox-bg" id="fancybox-bg-e">
            </div>
            <div class="fancybox-bg" id="fancybox-bg-se">
            </div>
            <div class="fancybox-bg" id="fancybox-bg-s">
            </div>
            <div class="fancybox-bg" id="fancybox-bg-sw">
            </div>
            <div class="fancybox-bg" id="fancybox-bg-w">
            </div>
            <div class="fancybox-bg" id="fancybox-bg-nw">
            </div>
            <div id="fancybox-content">
            </div>
            <a id="fancybox-close">
            </a>
            <div id="fancybox-title">
            </div>
            <a href="javascript:;" id="fancybox-left">
                <span class="fancy-ico" id="fancybox-left-ico">
                </span>
            </a>
            <a href="javascript:;" id="fancybox-right">
                <span class="fancy-ico" id="fancybox-right-ico">
                </span>
            </a>
        </div>
    </div>
</body>
</html>