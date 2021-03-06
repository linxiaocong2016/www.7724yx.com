<?php

class KeditorWidget extends CWidget
{
 	public $model;
 	public $name;
 	public $textareaOptions;
	public function init()
	{
	}

	public function run()
	{
		$this->widget ( 'ext.KEditor.KEditor', array (
				'model'=>$this->model,  //传入form model
				'name'=>$this->name, //设置name
				'textareaOptions'=>$this->textareaOptions,
				'properties' => array (
						'uploadJson' => '/xmsb/game/upload',
						'fileManagerJson' => '/xmsb/game/manageJson',
						'newlineTag' => 'br',
						'filterMode' => false,
						'allowFileManager' => true,
						'afterCreate' => "js:function() {
                        K('#ChapterForm_all_len').val(this.count());
                        K('#ChapterForm_word_len').val(this.count('text'));
                    }",
						'afterChange' => "js:function() {
                        K('#ChapterForm_all_len').val(this.count());
                        K('#ChapterForm_word_len').val(this.count('text'));
                    }"
				),
		) );
	}
}


