// JavaScript Document
$(function() {
    //头部搜索框
    var Wwidth = $(window).width();
    $(".top_search").width(Wwidth - 155);
    //更多攻略
    $(".more_radier dl dd").width(Wwidth - 55)
    //天气
    $(".wether_in img").click(function() {
        if ($(".weather_list ").css("display") == "none") {
            $(".weatherbg,.weatherbg img").animate({height: "172px"}, 300);
            $(".weather_list ").slideDown(200);

        } else {
            $(".weather_list ").slideUp(700);
            $(".weatherbg").animate({height: "100px"}, 500);
        }

    })
    //弹出定位
    $(".local_city").click(function(event) {
        $(".opacity_bg,.city_box").show();
    })

//取消
    $(".box_list .a1").click(function() {
        $(".opacity_bg,.city_box").hide();
    })

    //确定
    $(".box_bt").click(function() {
        $(".opacity_bg,.city_box").hide();
        if ($("#rbtn_manual").attr("class") == "hover")
        {
            var lvProvice = $("#s_province").val();
            var lvCity = $("s_city").val();
            var lvCounty = $("s_county").val();
            addCookie('op_provice', escape(lvProvice), 365);
            addCookie('op_city', escape(lvCity), 365);
            addCookie('op_county', escape(lvCounty), 365);
        }
    })


    $(".box_list .tr1").click(function() {
        $(".box_list span").attr("class", "")
        $(this).find("span").attr("class", "hover")
    })

    //展开分类
    var bodyH = $(".big").height();
    $(".zhe").height(bodyH);

    $(".list3 .title").click(function() {
        $(".list3 #site").fadeToggle(500);
        $(".list3 #site").css("border-top", "1px solid #dfdfdf");


    });
    $(".list4 #title2").click(function() {
        $(".list4 #site2").fadeToggle(500);
        $(".list4 #site2").css("border-top", "1px solid #dfdfdf");
    });
/*
    //加载城市信息
    var lvC_city = unescape(getCookie('city'));
    var lvC_tempnow = unescape(getCookie('tempnow'));
    var lvC_weather = unescape(getCookie('weather'));
    var lvC_wt = unescape(getCookie('wt'));
    if (lvC_city)
    {
        $(".local_city").html(lvC_city);
        $(".big_t").html(lvC_tempnow);
        $(".small_t").html(lvC_weather);
        $("#imgwt").attr("src", "/images/ubrowser/black/weather_icon_" + lvC_wt + "_w.png");
        $("#imgwtbg").attr("src", "/images/ubrowser/weather/" + lvC_wt + ".jpg");
        $(".header").show();
        $(".weatherbg").show();
    }
    else {
        getLocation();
    }
*/
});


window.onresize = function() {
    //头部搜索框
    var Wwidth = $(window).width();
    $(".top_search").width(Wwidth - 155);
    //更多攻略
    $(".more_radier dl dd").width(Wwidth - 55);
}

function getLocation()
{
    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(showPosition);
    }
    else {
        //x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position)
{
    $.ajax({
        type: "GET",
        url: "/index.php?r=ubrowser/index/getCity&longi=" + position.coords.longitude + "&lanti=" + position.coords.latitude,
        success: function(city) {
            addCookie('city', escape(city), 365);
            $(".local_city").html(city);
            getWeacherInfo(city);
        }});
}

/**
 * 取得天气信息
 */
function getWeacherInfo(city, province, county) {
    $.ajax({
        type: "GET",
        url: "/index.php?r=ubrowser/index/GetToDayWeather&location=" + city + "&province=" + province + "&county=" + county,
        dataType: "json",
        success: function(msg) {
            $(".header").show();
            $(".weatherbg").show();
            var tempnow = msg.tempnow;
            var weather = msg.temperature.replace(' ~ ', '～') + msg.weather;
            var wt = msg.wt;
            addCookie('tempnow', escape(tempnow), 3)
            addCookie('weather', escape(weather), 3);
            addCookie('wt', escape(wt), 3);
            $(".big_t").html(tempnow);
            $(".small_t").html(weather);
            $("#imgwt").attr("src", "/images/ubrowser/black/weather_icon_" + wt + "_w.png");
            $("#imgwtbg").attr("src", "/images/ubrowser/weather/" + wt + ".jpg");

            //载入预报
            $.ajax({
                type: "GET",
                url: "/index.php?r=ubrowser/index/GetWeatherForecast&location=" + city,
                dataType: "json",
                success: function(yubao) {
                    var lvTmp = '';
                    $(yubao).each(function(i) {
                        if (i > 0 && i < 6)
                            lvTmp += "<li><p>" + yubao[i].date + "<br/>" + yubao[i].temp.replace('℃', '') + "</p><img src=\"/images/ubrowser/white/weather_icon_" + yubao[i].wt + "_w.png\"/></li>";
                    });
                    $("#wtlist").html(lvTmp);
                }
            });
        }
    });
}


 