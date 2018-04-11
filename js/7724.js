function setTab(name, cursel, n) {
    for (i = 1; i <= n; i++) {
        var menu = document.getElementById(name + i);
        var con = document.getElementById("con_" + name + "_" + i);
        menu.className = i == cursel ? "hover" : "";
        con.style.display = i == cursel ? "block" : "none";
        if (cursel == 3)
            $("#feeds_more_ic").hide();
        else
            $("#feeds_more_ic").show();
    }
    $("#feeds_more_ic").html("点击查看更多");
    if (resut_msg)
        resut_msg = '没有更多内容了';
}

/**
 * 回复评论
 */
function replycomment(pID) {
    $(".opacity_bg,.new_mybox").show();
    $(".textarea").focus();
    $("#replycomment_id").val(pID);
}
 
function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
    var expires = new Date();
    if (cookieValue == '' || seconds < 0) {
        cookieValue = '';
        seconds = -2592000;
    }
    expires.setTime(expires.getTime() + seconds * 1000);
    document.cookie = escape("7724_" + cookieName) + '=' + escape(cookieValue)
            + (expires ? '; expires=' + expires.toGMTString() : '')
            + (path ? '; path=' + path : '/')
            + (domain ? '; domain=' + domain : '')
            + (secure ? '; secure' : '');
}

function getcookie(name, nounescape) {
    name = "7724_" + name;
    var cookie_start = document.cookie.indexOf(name);
    var cookie_end = document.cookie.indexOf(";", cookie_start);
    if (cookie_start == -1) {
        return '';
    } else {
        var v = document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length));
        return !nounescape ? unescape(v) : v;
    }
}
//使用方法：setCookie('username','Darren',30)
function hideAd() {
    setcookie("adtime", "1", 60*60, '/');
    $('#yb_box').hide();
}
 

$(function() {
    //焦点隐藏搜索下
//    var Wwidth = $(window).width();
//    $(".search_tx").width(Wwidth - 90);
//    $(".search_box").width(Wwidth - 85);
//
//    $(".search_tx").focus(function() {
//        //$(".search_center").fadeOut();
//        $(".search_box").show();
//    });
//    $(".search_tx").blur(function() {
//        //$(".search_box").hide()
//    });
//显示广告
    var lvCookieValue = getcookie("adtime");
    if (lvCookieValue != "1")
        $('#yb_box').show();
    else
        $('#yb_box').hide();

    //焦点隐藏搜索下
    $(".search_tx").focus(function() {
        // $(".search_center").fadeOut();

    })



    var boxW = $(".mybox").width();
    $(".textarea").width(boxW - 40);

    var awidth = $(".girl_list li").width();
    $(".girl_list li a").width(awidth - 4);
    //评论
    $(".comment p.p2").click(function() {
        $(".opacity_bg,.mybox").show();
        $("#content").focus();
    })

    /**
     * 回复评论
     */
//    $(".new_comnent li .p3 img").click(function() {
//        $(".opacity_bg,.new_mybox").show();
//        $(".textarea").focus();
//    })
    $(".mybox_bt1,.mybox_bt2").click(function(i, j) {
        $(".opacity_bg,.new_mybox").hide();
        //提交回复
        if (this.id == "replybutton")
        {
            $.post("/index.php?r=index/comment", {"method": "reply", "id": $("#replycomment_id").val(), "title": CITY, "content": $("#replycontent").val(), "gameid": GAMEID, "gamename": GAMENAME}, function(data) {
                if (data == 1) {
                    alert("回复成功！");
                    $("#content").val("");
                    location.href = location.href;
                }
                else
                    alert("回复失败，请稍候操作！");
            });
        }

    })

    $(".mybox_bt1,.mybox_bt2").click(function() {
        $(".opacity_bg,.mybox").hide();

    })
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


    $(".introd_con img").each(function() {
        var ImgWidth = $(this).width()
        var BodyWidth = $(document).width();
        var _ImgWidth = $(".introd_con").width()
        if (BodyWidth >= 220 && BodyWidth <= 360)
        {
            if (ImgWidth >= 320) {
                $(this).width(_ImgWidth)
            }
        } else if (BodyWidth > 360 && BodyWidth <= 510)
        {
            if (ImgWidth >= 360) {
                $(this).width(_ImgWidth)
            }
        } else if (BodyWidth > 510 && BodyWidth <= 700)
        {
            if (ImgWidth > 510) {
                $(this).width(_ImgWidth)
            }
        } else if (BodyWidth > 700 && BodyWidth <= 900)
        {
            if (ImgWidth > 700) {
                $(this).width(_ImgWidth)
            }
        } else if (BodyWidth > 900 && BodyWidth <= 1368)
        {
            if (ImgWidth > 900) {
                $(this).width(_ImgWidth)
            }

        } else {
            if (ImgWidth > 800) {
                $(this).width(_ImgWidth)
            }
        }
    });
});

window.onresize = function() {
    var awidth = $(".girl_list li").width();
    $(".girl_list li a").width(awidth - 4);



    $(".introd_con img").each(function() {
        var ImgWidth = $(this).width()
        var BodyWidth = $(document).width();
        var _ImgWidth = $(".introd_con").width()
        if (BodyWidth >= 220 && BodyWidth <= 360)
        {
            if (ImgWidth >= 320) {
                $(this).width(_ImgWidth)
            }
        } else if (BodyWidth > 360 && BodyWidth <= 510)
        {
            if (ImgWidth >= 360) {
                $(this).width(_ImgWidth)
            }
        } else if (BodyWidth > 510 && BodyWidth <= 700)
        {
            if (ImgWidth > 510) {
                $(this).width(_ImgWidth)
            }
        } else if (BodyWidth > 700 && BodyWidth <= 900)
        {
            if (ImgWidth > 700) {
                $(this).width(_ImgWidth)
            }
        } else if (BodyWidth > 900 && BodyWidth <= 1368)
        {
            if (ImgWidth > 900) {
                $(this).width(_ImgWidth)
            }

        } else {
            if (ImgWidth > 800) {
                $(this).width(_ImgWidth)
            }
        }
    });

}