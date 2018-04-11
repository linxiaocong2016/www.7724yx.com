<?php 

 $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'../common/table_list',
	'sortableAttributes'=>array('title','addtime','id'),
	 'template'=>'<div class="summary">{summary}</div><div class="sorter">{sorter}</div><div class="list">{items}</div><div class="pager">{pager}</div>',
	'pager' => array(//pager 的配置信息。默认为<code>array('class'=>'CLinkPager')</code>.也可以自己配置
	  //定义分页器的要调用的css文件，false为不调用，不调用则需要亲自己css文件里写这些样式
        'header'=>'',//定义的文字将显示在pager的最前面
        'footer'=>'',//定义的文字将显示在pager的最后面
        'firstPageLabel'=>'首页',//定义首页按钮的显示文字
        'lastPageLabel'=>'尾页',//定义末页按钮的显示文字
        'nextPageLabel'=>'下一页',//定义下一页按钮的显示文字
        'prevPageLabel'=>'前一页',//定义上一页按钮的显示文字
	    'lastPageCssClass'=>'',
	    'firstPageCssClass'=>'',
   
   ),
       'emptyText'=>'暂时没有数据',  
)); ?>