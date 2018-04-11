<div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span>
       <em>专题</em>
    </div>

    <!--左边列表-->
    <div class="subject_list">
       <dl>
       <?php 
       	foreach($lvList as $k=>$v):
       		$url=Urlfunction::getSubjectDetailUrl($v['id']);
       ?>
           <dd>
               <div class="p1"><a title='<?php echo $v['name']?>' href="<?php echo $url;?>"><img src="<?php echo Urlfunction::getImgURL($v['img'])?>"></a></div>
               <div class="p2">
                  <p><a title='<?php echo $v['name']?>' href="<?php echo $url;?>"><?php echo $v['name']?></a></p>
                  <span><?php echo strip_tags($v['content']);?></span>
                  <em><?php echo date("Y-m-d H:i",$v['report_time'])?></em>
               </div>
           </dd>
       <?php endforeach;?>
       </dl>
       <!--页码-->
       <div class="newyiiPager" >
               <?php  $this->widget('CLinkPager',array(
		               		'header'=>"共{$pageCount}页：",
							'firstPageLabel' => '首页',
							'lastPageLabel' => '末页',
							'prevPageLabel' => '上一页',
							'nextPageLabel' => '下一页',
							'maxButtonCount'=>12,
							'pages'=>$pages,
							)
						);
				?>
              </div>
    </div>
    <!--右边-->
     <div class="h5_right" style="margin-top:10px">
        <?php include dirname(__FILE__)."/../common/hot_subject.php";?>
		<?php include dirname(__FILE__)."/../common/hot_game.php";?>
     </div>
 </div>