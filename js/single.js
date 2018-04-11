
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
    var downurl = '';
    var showDownText = '下载';
    if (value.show_type == "1")
    {
        url = '/dj/' + value.game_id + '/';
    }
    else if (value.show_type == "2")
    {
        url = '/wy/' + value.game_id + '/';
    }
    else if (value.show_type == "3")
    {
        url = '/online/' + value.game_id + '/';
    }
    else if (value.show_type == "5")
    {
        url = '/crack/' + value.game_id + '/';
    }
    else
    {
        url = '/wy/' + value.game_id + '/';
    }
    if (value.show_type == 1 || value.show_type == 5)
    {
        showDownText = '下载';
        downurl = 'http://www.pipaw.com/www/oldgame/downrecord/softid/' + value.game_id + '/phone_type/1/flag/' + G_FLAG + '/';
    }
    else if (value.show_type == 2)
    {
        showDownText = '下载';
        downurl = 'http://gameapi.pipaw.com/v' + value.version_id + '/f' + G_FLAG + '?a=7724&c=' + encodeURI(location.href);
    }
    else
    {
        downurl = '/online/' + value.game_id + '/';
        showDownText = '开始';
    }
    var lvContent = '<dt onclick="location.href=\'' + url + '\';"><p class="p1"><a href="' + url + '"><img src="' + value.game_logo + '"/></a></p><p class="p2"><a href="' + url + '">' + value.game_name + '</a>';
    var lvTmp = value.game_score.split(',');

    for (j = 0; j < lvTmp[0]; j++) {
        lvContent += '<img src="/img/red_star.png" />';
    }
    if (lvTmp[1] > 0) {
        lvContent += '<img src="/img/hafe_star.png" />';
    }
    for (j = 0; j < lvTmp[2]; j++) {
        lvContent += '<img src="/img/gray_star.png" />';
    }

    if (showtype == 'online_ph' || showtype == 'online_zx')
    {
        lvContent += '</p><p class="p3"><a href="' + downurl + '">' + showDownText + '</a></p></dt>';
    }
    else if (showtype == 'search')
    {
        if (value.show_type == 1 || value.show_type == 2)
            lvContent += '<span>大小：' + value.game_size + 'M &nbsp;&nbsp;下载：' + value.game_downclick + '</span></p><p class="p3"><a href="' + downurl + '">' + showDownText + '</a></p></dt>';

    }
    else {
        lvContent += '<span>大小：' + value.game_size + 'M &nbsp;&nbsp;下载：' + value.game_downclick + '</span></p><p class="p3"><a href="' + downurl + '">' + showDownText + '</a></p></dt>';
    }
    return lvContent;

}

function getIOSListContent(value, showtype)
{
    //    game_id,game_name,game_logo,game_type,game_score,show_type,version_id,game_size,game_downclick 
    var lvTmp = value.game_score.split(',');
    var lvStar = '';
    for (j = 0; j < lvTmp[0]; j++) {
        lvStar += '<img src="/img/red_star.png" height=\"12\" />';
    }
    if (lvTmp[1] > 0) {
        lvStar += '<img src="/img/hafe_star.png"  height=\"12\"/>';
    }
    for (j = 0; j < lvTmp[2]; j++) {
        lvStar += '<img src="/img/gray_star.png"  height=\"12\"/>';
    }

    var showDownText = '';
    var downurl = '';
    var url = '';
    if (value.show_type == 1)
    {
        url = '/ios/dj/' + value.game_id + '/';
        showDownText = '下载';
        downurl = 'http://www.pipaw.com/www/oldgame/downrecord/softid/' + value.game_id + '/phone_type/1/flag/' + G_FLAG + '/';
    }
    else if (value.show_type == 2)
    {
        url = '/ios/wy/' + value.game_id + '/';
        showDownText = '下载';
        downurl = 'http://gameapi.pipaw.com/v' + value.version_id + '/f' + G_FLAG + '?a=7724&c=' + encodeURI(location.href);
    }
    else
    {
        url = '/online/' + value.game_id + '/flag/' + G_FLAG + '/';
        downurl = '/online/' + value.game_id + '/flag/' + G_FLAG + '/';
        showDownText = '打开';
    }


    var lvContent = "    <li>\n\
<div class=\"pie_tu_list\"><a href=\"" + url + "\"><img src=\"" + value.game_logo + "\" width=\"72\" height=\"72\"></a></div>\n\
<div class=\"content_list\">\n\
          <p class=\"pipaw-title\"><a href=\"" + url + "\">" + value.game_name + "</a></p>\n\
          <p class=\"pipaw-info\">" + lvStar + "</p>\n\
           <span class=\"game-info-item\"></span>" + value.game_type + " | " + value.game_size + "M</div>\n\
                  <div class=\"pipaw-an3\"> <a href=\"" + downurl + "\">" + showDownText + " </a></div>\n\
    </li>";
    return lvContent;

}

function insertTemplate(object, page, rows, showtype, gametype, keywords, msg)
{
    var result;//返回结果
    var arr;
    $.ajax({
        type: "POST",
        url: '/index.php',
        async: false,
        dataType: 'json',
        data: "r=getitem/getdata&showtype=" + showtype + "&gametype=" + gametype + "&page=" + page + "&pagesize=" + rows + "&keyword=" + keywords,
        success: function(msg)
        {
            result = msg;
        },
        error: function(msg)
        {
            var ii = 0;
            ii++;
        }
    });


    if (result && result.list.length > 0)
    {
        var lvContent = '';
        if (showtype == 'tupian')
        {
            object = $("#Gallery_0");
            lvContent = '';
            var listCount = Math.ceil(result.list.length / 2);
            for (var i = 0; i < listCount; i++) {
                var value = result.list[i];
                lvContent += "<a href=\"/meinv/detail/" + value.id + "/\"><img src=\"http://img.811.cn/" + value.pic + "\"><p>" + value.pic_count + "</p></a>";
            }
            object.append(lvContent);

            object = $("#Gallery_1");
            lvContent = '';
            for (var i = listCount; i < result.list.length; i++) {
                var value = result.list[i];
                lvContent += "<a href=\"/meinv/detail/" + value.id + "/\"><img src=\"http://img.811.cn/" + value.pic + "\"><p>" + value.pic_count + "</p></a>";
            }
            object.append(lvContent);

            var awidth = $(".girl_list li").width();
            $(".girl_list li a").width(awidth - 4);
        }
        else if (showtype == "tupian_detail")
        {
            $.each(result.list, function(key, value) {
                lvContent += "<li><a href=\"http://img.pipaw.net/" + value.pic + "\"><img src=\"http://img.pipaw.net/" + value.pic + "\"></a></li>";
            });
            object.append(lvContent);
        }
        else if (showtype == "ios_list_1" || showtype == "ios_list_2" || showtype == "ios_search" || showtype == 'ios_free')
        {
            $.each(result.list, function(key, value) {
                lvContent += getIOSListContent(value, showtype);
            });
            object.append(lvContent);
        }
        else if (showtype == "game_detail")
        {
            $.each(result.list, function(key, value) {
                lvContent += " <li><span class=\"span2\">" + value.content + "</span><p class=\"p3\"><span>" + value.create_time + "</span></p></li>";
            });
            object.append(lvContent);
        }
        else
        {
            $.each(result.list, function(key, value) {
                lvContent += getContent(value, showtype);
            });
            object.append(lvContent);
        }


        if (navigator.userAgent.toLowerCase().indexOf('micromessenger') >= 0) {
            if (showtype == "index_ph" || showtype == "index_zx" || showtype == "crack_ph" || showtype == "crack_zx" || showtype == "webgame_ph"
                    || showtype == "webgame_zx" || showtype == "list_zx_1" || showtype == "list_zx_5" || showtype == "list_zx_2"
                    || showtype == "list_ph_1" || showtype == "list_ph_5" || showtype == "list_ph_2" || showtype == "search"
                    || showtype == "ios_search" || showtype == "ios_list_1" || showtype == "ios_list_2" || showtype == "ios_free")
            {
                $(".p3 a").click(function() {
                    if (navigator.userAgent.toLowerCase().indexOf('micromessenger') >= 0)
                    {
                        if (document.getElementById('mcover'))
                        {
                            this.href = '';
                            document.getElementById('mcover').style.display = 'block';
                            return false;
                        }
                    }
                    return true;

                });
            }
        }

        if (result.list.length < rows)
        {
            clearTimeout(settime);
            $("#feeds_more_ic").html(msg);
        }
    }
    else if (result && result.list.length == 0)
    {
        clearTimeout(settime);
        $("#feeds_more_ic").html(msg);
    }
    else
    {
        clearTimeout(settime);
        $("#feeds_more_ic").html('数据加载失败.');
    }

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