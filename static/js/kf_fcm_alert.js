var myAlert = document.getElementById("gg_alert"); 
myAlert.innerHTML='<h3>亲爱的玩家：</h3><p>目前在线申请功能正在升级，暂无法进行提交。</p><p>如您需要提交，麻烦您将所需资料连同申请表格下载并填写完毕后，通过您的邮箱发送到<span class="gg_alert_a">2885339244@qq.com</span></p><p>如过程中有任何疑问，您可以联系客服<span class="gg_alert_a">QQ：2885339244</span></p><div class="p80"><a class="jh_wysq_btn" href="javascript:void(0);" id="alertBtn1">确定</a></div><div class="jh_close" id="alertBtn2"></div>';
var mClose1 = document.getElementById("alertBtn1"); 
var mClose2 = document.getElementById("alertBtn2"); 
function alertMsg() 
{ 
	myAlert.style.display = "block"; 
    myAlert.style.position = "absolute"; 
    myAlert.style.top = "50%"; 
    myAlert.style.left = "50%"; 
    myAlert.style.marginTop = "-160px"; 
    myAlert.style.marginLeft = "-228px";
    myAlert.style.zIndex = "501";
    mybg = document.createElement("div");
    mybg.setAttribute("id","mybg");
    mybg.style.background = "#000";
    mybg.style.width = "100%";
    mybg.style.height = "100%";
    mybg.style.position = "fixed";
    mybg.style.top = "0";
    mybg.style.left = "0";
	mybg.style.bottom = "0";
    mybg.style.zIndex = "500";
    mybg.style.opacity = "0.3";
    mybg.style.filter = "Alpha(opacity=30)"; 
    document.body.appendChild(mybg);
    document.body.style.overflow = "hidden";
}
mClose1.onclick = function()
{ 
    myAlert.style.display = "none";
    mybg.style.display = "none";
}
mClose2.onclick = function()
{ 
    myAlert.style.display = "none";
    mybg.style.display = "none";
}