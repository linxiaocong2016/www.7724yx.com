<?php

class Game extends CActiveRecord {
	public $mb_type;
	
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'game';
    }

    public function behaviors() {
        return array();
    }

    public function rules() {
        return array(
            array(
                'game_name,game_type,pinyin',
                'required'
            ),
            array(
                'game_name,pinyin',
                'unique'
            ),
            array(
                'game_name',
                'length',
                'max' => 100
            ),
            array(
                'pinyin, game_logo,seo_title,seo_keyword,seo_description,type',
                'length',
                'max' => 255
            ),
            array(
                'pinyin_first_letter',
                'length',
                'max' => 1
            ),
            array(
                'game_id,game_logo, game_name, pinyin, pinyin_first_letter, game_type, short_introduce, share_title, share_desc,
				game_logo, game_visits, star_level,game_album,tag,source,status,weight,style,country,
				check_user,edit_user,add_user,rand_visits,time,has_paihang, scoreunit, scoreformat,
				update_time,scoreorder,game_url,egretruntime,download_hezi_flag,download_hezi_gameimg,filter_flag,
				enter_show_flag,pipaw_id,pipaw_suffix,pipaw_channelid,mb_type,wy_dj_flag',
                'safe'
            ),
        );
    }

    public function search() {
        $a = Yii::app()->getController()->getAction()->id;
        $criteria = new CDbCriteria ();
        if(!$_GET ['status'] && $a != 'mytask' && $a!="rank")
            $criteria->addColumnCondition(array( 'edit_user' => null ));
        if($a == 'mytask')
            $criteria->compare('edit_user', $this->getUserName());
        else
            $criteria->compare('status', $_GET ['status']);
        if($_GET['Game']['style'])
            $criteria->compare('style', $_GET['Game']['style']);
        if($_GET['Game']['has_paihang'] && $_GET['Game']['has_paihang'] != "a") {
            $criteria->compare('has_paihang', intval(str_replace("a", "", $_GET['Game']['has_paihang'])));
        }
        Tools::print_log($criteria);

        if($_GET['Game']['country'])
            $criteria->compare('country', $_GET['Game']['country']);
        $criteria->compare('game_name', $_GET ['game_name'], true);
        if($_GET ['Game'] ['game_type'])
            $criteria->compare('game_type', ',' . $_GET ['Game'] ['game_type'] . ',', true);
        if($_GET['begin_time']) {
            $_GET ['begin_time'] = $_GET ['begin_time'] . ' 00:00:00';
            $criteria->compare('time >', strtotime($_GET ['begin_time']));
        }
        if($_GET['end_time']) {
            $_GET ['end_time'] = $_GET ['end_time'] . '23:59:59';
            $criteria->compare('time <', strtotime($_GET ['end_time']));
        }
        
        $_GET['pipaw_id_s']=isset($_GET['pipaw_id_s'])?$_GET['pipaw_id_s']:'';
        if($_GET['pipaw_id_s']!='') {
        	if($_GET['pipaw_id_s']=='0'){
        		$criteria->compare('pipaw_id', $_GET['pipaw_id_s']);
        	}else{
        		$criteria->compare('pipaw_id >', $_GET['pipaw_id_s']);
        	}        	
        }

        if($a == 'rank') {
            $criteria->order = 'game_visits + weight desc';
            $criteria->compare('status', 3);
        } elseif($_GET ['status'] == 3)
            $criteria->order = 'time desc,game_id desc';
        else
            $criteria->order = 'game_id desc';


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 18
            )
        ));
    }

    public function attributeLabels() {
        return array(
            'game_id' => '游戏ID',
            'game_name' => '游戏名称',
            'pinyin' => '拼音',
            'pinyin_first_letter' => '拼音首字母',
            'wy_dj_flag' => '游戏分类',
            'game_type' => '具体类型',
            'short_introduce' => '简介',
            'game_logo' => '游戏图标',
            'game_visits' => '访问次数',
            'game_album' => '游戏相册',
            'seo_title' => 'seo标题',
            'seo_keyword' => 'seo关键字',
            'seo_description' => 'seo描述',
            'star_level' => '星级',
            'weight' => '权重',
            'tag' => '标签',
            'style' => '横竖版',
            'country' => '国内外',
            'check_user' => '审核人',
            'edit_user' => '采集人',
            'add_user' => '新增人',
            'rand_visits' => '随机浏览量',
            'source' => '采集地址',
            'time' => '时间',
            'has_paihang' => '排行',
            'scoreunit' => '分数单位',
            'scoreformat' => '分数格式化',
            'update_time' => '发布时间',
            'scoreorder'=>'分数顺序',
            'game_url'=>'游戏地址',
            'egretruntime'=>'EgretRuntime',
        	'download_hezi_flag'=>'是否下载盒子',
        	'download_hezi_gameimg'=>'下载盒子时<br/>显示的游戏图片',
			'share_title'=>'分享标题',
        	'share_desc'=>'分享描述',
			'enter_show_flag'=>'游戏相关展示',
			'pipaw_suffix'=>'入库琵琶网<br/>游戏名后缀',
        	'pipaw_channelid'=>'入库琵琶网游戏<br/>地址渠道id',
        		'type'=>"推荐位置",
        );
    }

    public function get_cache() {
        $cache = Yii::app()->cache->get(__CLASS__);
        if(empty($cache)) {
            $arr = self::model()->findAll();
            foreach( $arr as $k => $v ) {
                $cache [$v->game_id] = $v->attributes;
            }
            Yii::app()->cache->set(__CLASS__, $cache, 3600);
        }
        return $cache;
    }
    
    
    public static  function get_cache_admin(){
    	$name="xmsb_game";
    	$cache=Yii::app()->cache->get($name);
    	if(!$cache){
    		$sql="select game_id,game_name,pinyin from game";
    		$res=yii::app()->db->createCommand($sql)->queryAll();
    		foreach($res as $v){
    			$game_id=$v['game_id'];
    			unset($v['game_id']);
    			$cache[$game_id]=$v;
    		}
    		Yii::app()->cache->set($name, $cache, 3600);
    	}
    	return $cache;
    }
    

    public function getUserName() {
        return Yii::app()->session ['userInfo']['realname'] ? Yii::app()->session ['userInfo']['realname'] : Yii::app()->session ['userInfo']['username'];
    }

    public function getGameName($pGameID) {
        $lvSQL = "select * from game where game_id=" . $pGameID;
        $lvInfo = Yii::app()->db->createCommand($lvSQL)->queryRow();
        if($lvInfo)
            return $lvInfo['game_name'];
        return "";
    }

    /**
     * 取得分数格式化值
     * @param type $pScore 
     * @param type $pFormat $value=getHour($pScore);  $value=$this->getScale($pScore,'秒|分|小时',60); 
     */
    public function getScoreString($pScore, $pFormat) {
        eval($pFormat);
        return $value;
    }

    /**
     * 取得进制值
     * @param type $pScore 3805
     * @param type $pUnit 秒|分|小时
     * @param type $pIntValue 60
     * @return type String
     */
    public function getScale($pScore, $pUnit, $pIntValue) { 
        $lvArrUnit = split('[|]', $pUnit);
        $lvResult = "";
        $lvTMP = 0;
        $lvTMP2 = $pScore;
        $lvCount = count($lvArrUnit);
        //echo $lvCount;
        //只有一个单位
        if($lvCount == 1) {
            $lvResult = $pScore . $pUnit;
        }
        //多个单位
        else {
            for( $index = 0; $index < count($lvArrUnit); $index++ ) {
                $lvTMP = $lvTMP2 % $pIntValue;
                $lvResult = $lvTMP . $lvArrUnit[$index] . $lvResult;
                if($lvTMP2 >= $pIntValue)
                    $lvTMP2 = floor($lvTMP2 / $pIntValue);
                else
                    break;
            }
        }

        return $lvResult;
    }

}
