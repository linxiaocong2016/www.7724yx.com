
$(function() {
    $(".detail_con img").each(function() {
        var ImgWidth = $(this).width()
        var BodyWidth = $(document).width();
        var _ImgWidth = $(".detail_con").width();
        if (BodyWidth >= 220 && BodyWidth <= 360)
        {
            if (ImgWidth >= 320)
            {
                $(this).width(_ImgWidth)
            }
        }
        else if (BodyWidth > 360 && BodyWidth <= 510)
        {
            if (ImgWidth >= 360)
            {
                $(this).width(_ImgWidth)
            }
        }
        else if (BodyWidth > 510 && BodyWidth <= 700)
        {
            if (ImgWidth > 510)
            {
                $(this).width(_ImgWidth)
            }
        }
        else if (BodyWidth > 700 && BodyWidth <= 900)
        {
            if (ImgWidth > 700)
            {
                $(this).width(_ImgWidth)
            }
        }
        else if (BodyWidth > 900 && BodyWidth <= 1368)
        {
            if (ImgWidth > 900)
            {
                $(this).width(_ImgWidth)
            }
        }
        else
        {
            if (ImgWidth > 800)
            {
                $(this).width(_ImgWidth)
            }
        }
    });
});

window.onload = function()
{
    //var obj11 = document.getElementById("ME_fixed");
    //var top11 = getTop(obj11);
    var isIE6 = /msie 6/i.test(navigator.userAgent);
    window.onscroll = function()
    {
        var bodyScrollTop = document.documentElement.scrollTop || document.body.scrollTop;
//        if (bodyScrollTop > top11)
//        {
//            obj11.style.position = (isIE6) ? "absolute" : "fixed";
//            obj11.style.top = (isIE6) ? bodyScrollTop + "px" : "0px";
//            obj11.style.marginTop = "0px";
//            obj11.style.zIndex = "1000";
//        }
//        else
//        {
//            obj11.style.position = "static";
//            obj11.style.marginTop = "5px";
//        }
    }

    function getTop(e)
    {
        if (e)
        {
            var offset = e.offsetTop;
            if (e.offsetParent != null)
                offset += getTop(e.offsetParent);
            return offset;
        }
    }
}
//改变窗口大小的时候再次计算各个模块高度 
window.onresize = function()
{
    $(".detail_con img").each(function() {
        var ImgWidth = $(this).width()
        var BodyWidth = $(document).width();
        var _ImgWidth = $(".detail_con").width();

        if (BodyWidth >= 220 && BodyWidth <= 360)
        {
            if (ImgWidth >= 320)
            {
                $(this).width(_ImgWidth);
            }
        }
        else if (BodyWidth > 360 && BodyWidth <= 510)
        {
            if (ImgWidth >= 360)
            {
                $(this).width(_ImgWidth);
            }
        }
        else if (BodyWidth > 510 && BodyWidth <= 700)
        {
            if (ImgWidth > 510)
            {
                $(this).width(_ImgWidth);
            }
        }
        else if (BodyWidth > 700 && BodyWidth <= 900)
        {
            if (ImgWidth > 700)
            {
                $(this).width(_ImgWidth);
            }
        }
        else if (BodyWidth > 900 && BodyWidth <= 1368)
        {
            if (ImgWidth > 900)
            {
                $(this).width(_ImgWidth);
            }
        }
        else
        {
            if (ImgWidth > 800)
            {
                $(this).width(_ImgWidth);
            }
        }
    });
}

/*lgl代码*/
function myrefresh()
{
    window.location.reload();
}
function getContent(value, showtype)
{
    var url = '';
    if (value.showtype == "1")
    {
        url = '/ios/dj/' + value.gameid + '/';
    }
    else if (value.showtype == "2")
    {
        url = '/ios/wy/' + value.gameid + '/';
    }
    else if (value.showtype == "3")
    {
        url = '/ios/online/' + value.gameid + '/';
    }
    else
    {
        url = '/ios/wy/' + value.gameid + '/';
    }


    var lvTmp = value.gamescore.split(',');

    var lvStar = "";
    for (j = 0; j < lvTmp[0]; j++) {
        lvStar += '<img src="/img/red_star.png" />';
    }
    if (lvTmp[1] > 0) {
        lvStar += '<img src="/img/hafe_star.png" />';
    }
    for (j = 0; j < lvTmp[2]; j++) {
        lvStar += '<img src="/img/gray_star.png" />';
    }

    var lvContent = "  <dt  href=\"" + url + "\">             <p class=\"p1\"><a href=\"" + url + "\">\n\
<img src=\"" + value.gamelogo + "\"/></a></p>     \n\
<p class=\"p2\">     <a href=\"" + url + "\">" + value.gamename + "</a>     \n\
<em>" + lvStar + "</em> ";
 if(value.showtype == "1" || value.showtype == "2")   lvContent+="<span>" + value.gametype + " | " + value.gamesize + "M</span> ";
   lvContent+= "    </p> ";
 if(value.showtype == "1" || value.showtype == "2") lvContent+= "<p class=\"p3\"><a href=\"" + url + "\">详情</a></p>"
 else lvContent+= "<p class=\"p3\"><a href=\"" + url + "\">开始</a></p>"
     lvContent+=   "</dt>";
    return lvContent;

}

function insertTemplate(object, page, rows, showtype, gametype, keywords, msg)
{
    if (msg == "0")
        return false;
    var result;//返回结果
    var arr;
    $.ajax({
        type: "POST",
        url: '/index.php',
        async: false,
        dataType: 'json',
        data: "r=getiositem/getdata&showtype=" + showtype + "&gametype=" + gametype + "&page=" + page + "&pagesize=" + rows + "&keyword=" + keywords,
        success: function(msg)
        {
            result = msg;
        }
    });


    if (result && result.list.length > 0)
    {
        var lvContent = '';

        $.each(result.list, function(key, value) {
            lvContent += getContent(value, showtype);
        });
        object.append(lvContent);

        if (result.list.length < rows)
        {
            // clearTimeout(settime);
            // $("#feeds_more_ic").html(msg);
            return false;
        }
        return true;
    }
    else if (result && result.list.length == 0)
    {
//        clearTimeout(settime);
//        $("#feeds_more_ic").html(msg);
        return false;
    }
    else
    {
//        clearTimeout(settime);
//        $("#feeds_more_ic").html('数据加载失败.');
        return false;
    }
    return false;
}

var __sto = setTimeout;
window.setTimeout = function(callback, timeout, param) {
    var args = Array.prototype.slice.call(arguments, 2);
    var _cb = function() {
        callback.apply(null, args);
    }
    __sto(_cb, timeout);
}

$(function() {
    $(".lglindex li").each(function(n, value) {
        if ((n + 1) % 5 == 0) {
            $(this).find('a').addClass('no_line');
        }
    });
    $(".lglnews dd").each(function(n, value) {
        if ((n + 1) % 4 == 0) {
            $(this).find('a').addClass('no_line');
        }
    });
})