<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title>家长监护申请_哥们网企业站 - 向世界传播中华侠文化！</title>
<?php 
	Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/static/css/home01.css");
?>
<script type="text/javascript">
    var lastkey = 0;
    var tMark = null;
    function showDiv(key)
    {
        if(lastkey) document.getElementById("div_"+lastkey).style.display = "none";
        if(tMark)  clearTimeout(tMark);
        document.getElementById("div_"+key).style.display = "";
        lastkey = key;
    }

    function hideDiv(key)
    {

        if(lastkey)
            tMark = setTimeout('document.getElementById("div_' + lastkey +'").style.display="none";lastkey=0;')
    }

</script>
</head>

<body>
	<?php echo $content;?>
</body>
</html>