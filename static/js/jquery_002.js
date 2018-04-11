;(function($) {

	$.zprompt = $.zprompt || {version:'0.1.1'};

	var zprompt = function(node,opts) {

		var me=this,$me=$(this);
		var $mine=$(node); 

		$.extend(me, {
			onSbmi: function(callback){
				__onSbmi(callback);
			},
			options: function() {
				return opts;
			}
		});

		var $m_prompt = $mine;

		function __init__(){
			$m_prompt
				.bind("focus", function(){
					var _value = $.trim($(this).val());
					$(this).css("color", "");
					if(_value == $(this).attr(opts.tipAttr)){
						$(this).val("");
					}
				})
				.bind("blur", function(){
					if($(this).val() == ""){
						$(this).css("color", opts.tipColor);
						$(this).val($(this).attr(opts.tipAttr));
					}
				})
				.bind("dragover",function(){
					$mine.focus();
				})
				.bind("dragleave",function(){
					$mine.blur();
				});

				if($.trim($m_prompt.val()) == ""){
					$m_prompt
						.val($m_prompt.attr(opts.tipAttr))
						.css("color", opts.tipColor);
				}

				$($m_prompt[0].form).bind("submit", function(){
					return __onSbmi();
				});

			opts.onInit($mine, opts);
		}
		__init__();

		function __onSbmi(){
			return opts.onSbmi($mine, opts);
		}

	};


	$.fn.zprompt = function(conf) {

		var el = this.eq(typeof conf == 'number' ? conf : 0).data("zprompt");
		if (el) { return el; }

		var opts = {
			"tipAttr": "defstr",
			"tipColor": "#CCC",
			"onSbmi": function(){},
			"onInit": function(){},
			"api": false
		};

		$.extend(opts, conf);

		this.each(function() {
			el = new zprompt(this, opts);
			$(this).data("zprompt", el);
		});

		return opts.api ? el: this;

	};


})(jQuery);
