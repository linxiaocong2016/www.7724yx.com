function transdate(endTime){
	var date=new Date();
	date.setFullYear(endTime.substring(0,4));
	date.setMonth(endTime.substring(5,7)-1);
	date.setDate(endTime.substring(8,10));
	date.setHours(endTime.substring(11,13));
	date.setMinutes(endTime.substring(14,16));
	date.setSeconds(endTime.substring(17,19));
	return Date.parse(date);
}
function getDay(day){  
       var today = new Date(); 
       var targetday_milliseconds=today.getTime() + 1000*60*60*24*day;   
       today.setTime(targetday_milliseconds); //注意，这行是关键代码    
       var tYear = today.getFullYear();  
       var tMonth = today.getMonth();  
       var tDate = today.getDate();  
       tMonth = doHandleMonth(tMonth + 1);  
       tDate = doHandleMonth(tDate);  
       return tYear+"-"+tMonth+"-"+tDate;  
} 
function doHandleMonth(month){  
       var m = month;  
       if(month.toString().length == 1){  
          m = "0" + month;  
       }  
       return m;  
} 
$(function () {
	var header_cookie_key = 'header_cookie_key',tDay = getDay(1),tDay = tDay + ' 00:00:00';
	var header_cookie_data = Cookie(header_cookie_key);
	if(header_cookie_data){
		$('header .red_click').hide();
		return false;
	}
	
	$('#head_user_login').click(function(){
		var header_cookie_time = transdate(tDay) - new Date().getTime();
		var myDate = new Date();
		myDate.setTime(myDate.getTime() + header_cookie_time);
		setCookie(header_cookie_key, 1, myDate, "/");
		$('header .red_click').hide();
	});
});