  <div class="container clearfix"> 
   <div class="PadContent"> 
    <div class="TopBg"> 
     <div class="MainBox"> 
      <form method="get" id="paymentform"> 
       <div class="Left_box"> 
        <div class="L_box1"> 
         <div class="L_menu"> 
          <ul class=""> 
           <li class="m9 on"> <a class="pay_link" href="#"> <span>退款处理</span> </a> </li> 
          </ul> 
         </div> 
         <!-- 支付宝 --> 
         <div class="L_PayPage"> 
          <div class="LP_tit">
          	 选择充值方式：
          </div> 
          <div class="radio_box"> 
           <span class="r-list-box"> <input id="r2" value="1" name="paymoneytype" checked="checked" type="radio" /> <label for="r2" class="on">退款到支付宝</label> </span> 
           <span class="r-list-box"> <input id="r1" value="2" name="paymoneytype" type="radio" /> <label for="r1">退款到奇币</label> </span> 
          </div> 
          <div class="radio_box"> 
           <span class="r-list-box"> <label for="r1">退款帐号:</label> <input autocomplete="off" name="username" class="input_txt" type="text" /> <label class="username_notice"></label> </span> 
          </div> 
          <div class="radio_box"> 
           <span class="r-list-box"> <label for="r1">重复帐号:</label> <input autocomplete="off" name="re_username" class="input_txt" type="text" /> <label class="re_username_notice"></label> </span> 
          </div> 
          <input name="paymenttype" value="0" type="hidden" /> 

          <!-- 充值金额 start --> 
          <div class="MoneyBox"> 
	           <div class="MoneyBoxItem "> 
	            <p>退款金额</p> 
	            <div class="pay_money_conn p_texte"> 
	             <ul> 
	              <li> <input name="fee" id="order_amount_1" value="10" class="input_chk" checked="checked" type="radio" /> <label for="order_amount_1">10元</label> </li> 
	              <li> <input name="fee" id="order_amount_2" value="20" class="input_chk" type="radio" /> <label for="order_amount_2">20元</label> </li> 
	              <li> <input name="fee" id="order_amount_3" value="30" class="input_chk" type="radio" /> <label for="order_amount_3">30元</label> </li> 
	              <li> <input name="fee" id="order_amount_4" value="50" class="input_chk" type="radio" /> <label for="order_amount_4">50元</label> </li> 
	              <li> <input name="fee" id="order_amount_5" value="100" class="input_chk" type="radio" /> <label for="order_amount_5">100元</label> </li> 
	              <li> <input name="fee" id="order_amount_6" value="300" class="input_chk" type="radio" /> <label for="order_amount_6">300元</label> </li> 
	              <li> <input name="fee" id="order_amount_7" value="500" class="input_chk" type="radio" /> <label for="order_amount_7">500元</label> </li> 
	              <li> <input name="fee" id="order_amount_8" value="800" class="input_chk" type="radio" /> <label for="order_amount_8">800元</label> </li> 
	              <li> <input name="fee" id="order_amount_9" value="1000" class="input_chk" type="radio" /> <label for="order_amount_9">1000元</label> </li> 
	              <li> <input name="fee" id="order_amount_10" value="1500" class="input_chk" type="radio" /> <label for="order_amount_10">1500元</label> </li> 
	              <li> <input name="fee" id="order_amount_11" value="2000" class="input_chk" type="radio" /> <label for="order_amount_11">2000元</label> </li> 
	              <li> <input name="fee" id="order_amount_12" value="3000" class="input_chk" type="radio" /> <label for="order_amount_12">3000元</label> </li> 
	              <li> <input name="fee" id="order_amount_13" value="5000" class="input_chk" type="radio" /> <label for="order_amount_13">5000元</label> </li> 
	              <li> <input name="fee" id="order_amount_14" value="8000" class="input_chk" type="radio" /> <label for="order_amount_14">8000元</label> </li> 
	              <li> <input name="fee" id="order_amount_15" value="10000" class="input_chk" type="radio" /> <label for="order_amount_15">10000元</label> </li> 
	              <li class="lst"> 
	              <input name="fee" id="order_amount_input" value="-1" class="input_chk" type="radio" /> 
	              <label for="order_amount_input">其他:</label> 
	              <input autocomplete="off" name="fee1" class="input_txt" type="text" /> 元<span class="txt">*请填写1到10000之间的整数</span></li> 
	             </ul> 
	            </div> 
	           </div> 
			</div>
           <input value="立即提交" class="PayBtn" onclick="return false;" type="button"  id="submit_button"/> 
           <div class="WarmPrompt">
           </div> 
          </div> 
         </div> 
        </div> 
       </div>
      </form> 
     </div> 
    </div> 
   </div> 
   <div class="w-ftwrap"> 
    <div class="w-fnav"> 
     <div class="w-fnavbox"> 
     </div> 
    </div> 
   </div> 
   <script>
   $(function(){
	   $("#submit_button").click(function(){
			var username=$("#paymentform").find("input[name='username']").val();
			var re_username=$("#paymentform").find("input[name='re_username']").val();
			var radio_on=$(".pay_money_conn").find("input[type='radio']:checked").val();
			var fee1=$("#paymentform").find("input[name='fee1']").val();
			var fee1=parseInt(fee1);
			
			if(!username){
				alert("请输入退款账号");
				return false;
			} 
			
			if(!re_username){
				alert("请输入重复账号");
				return false;
			}

			if(radio_on==-1){
				if(isNaN(fee1)||!fee1){
					alert("请填写1到10000之间的整数");
					return false;
				}
			}
			alert("提交成功！请等待审核！");
			window.location.href='/';		
		 })
	  })
   </script>