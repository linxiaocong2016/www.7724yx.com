/**
 * 回复评论
 */
function replycomment(pID) {
    $(".opacity_bg,.new_mybox").show();
    $(".textarea").focus();
    $("#replycomment_id").val(pID);
}


function setTab(name, cursel, n) {
    for (i = 1; i <= n; i++) {
        var menu = document.getElementById(name + i);
        if (menu) {
            var con = document.getElementById("con_" + name + "_" + i);
            menu.className = i == cursel ? "hover" : "";
            con.style.display = i == cursel ? "block" : "none";
        }
        if (cursel == 3)
            $("#feeds_more_ic").hide();
        else
            $("#feeds_more_ic").show();
    }
    $("#feeds_more_ic").html("点击查看更多");
    if (resut_msg)
        resut_msg = '没有更多内容了';
}

//计算输入的字符个数

function checkLength(which) {
    var maxChars = 250;
    if (which.value.length < maxChars) {
        which.value = which.value.substring(0, maxChars);
        var curr = maxChars - which.value.length;
        document.getElementById("chLeft").innerHTML = curr.toString();
    } else {
        alert("字数超过了")
    }
}

$(function() {
    var Wwidth = $(window).width();
    $(".search_tx").width(Wwidth - 70);


    var boxW = $(".mybox").width();
    $(".textarea").width(boxW - 40);

    var awidth = $(".girl_list li").width();
    $(".girl_list li a").width(awidth - 4);
    //评论
    $(".comment p.p2").click(function() {
        $(".opacity_bg,.mybox").show();
        $(".textarea").focus();
    })
    $(".mybox_bt1,.mybox_bt2").click(function() {
        $(".opacity_bg,.mybox").hide();

    })

    //回复评论
    $(".mybox_bt1,.mybox_bt2").click(function(i, j) {
        $(".opacity_bg,.new_mybox").hide();
        //提交回复
        if (this.id == "replybutton")
        {
            $.post("/index.php?r=index/comment", {"method": "reply", "id": $("#replycomment_id").val(), "title": CITY, "content": $("#replycontent").val(),"gameid":GAMEID,"gamename":GAMENAME}, function(data) {
                if (data == 1) {
                    alert("回复成功！");
                    $("#content").val("");
                    location.href = location.href;
                }
                else
                    alert("回复失败，请稍候操作！");
            });
        }

    });


    //显示隐藏简介、评论
    $(".introd").click(function() {
        $(this).children("span").toggleClass("span1");
        $(this).next("div").toggleClass("newopen");
    })
    $(".mybox p.p2 span").click(function() {
        var index = this.id.replace("spancomment", "");
        var indexValue = parseInt(index) + 1;
        $("#score").val(indexValue);
        $(".mybox p.p2 span").removeClass("sel");
        for (i = 0; i < indexValue; i++) {
            $("#spancomment" + i).addClass("sel")
        }
    });
    //显示隐藏筛选
    $(".girl_top p").click(function() {
        $(this).toggleClass("p1")
        $(this).prev("ul").toggleClass("hidden_ul")
    })
    //更多、收起	
    $(".more").click(function() {
        if ($(this).html() == "更多&gt;&gt;") {

            $(".tit_more").show();
            $(this).html("&lt;&lt;收起");
        } else {

            $(".tit_more").hide();
            $(this).html("更多&gt;&gt;");

        }

    })

});

window.onresize = function() {
    var Wwidth = $(window).width();
    $(".search_tx").width(Wwidth - 70);
    var awidth = $(".girl_list li").width();
    $(".girl_list li a").width(awidth - 4);





}


