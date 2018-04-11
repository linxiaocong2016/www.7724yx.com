<?php
/**
 * SeoHead class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

class SeoHead extends CWidget
{
	/**
	 * @property array the configuration for the title. 
	 * Defaults to <code>array('class'=>'ext.seo.widgets.SeoTitle')</code>.
	 * @see enableTitle
	 */
	public $title = array('class'=>'ext.seo.widgets.SeoTitle');
	/**
	 * @property boolean whether to enable the title.
	 */
	public $enableTitle = false;
	/**
	 * @property array the page http-equivs.
	 */
	public $httpEquivs = array();
	/**
	 * @property string the page meta description.
	 */
	public $defaultDescription;
	/**
	 * @property string the page meta keywords.
	 */
	public $defaultKeywords;
	/**
	 * @property array the page meta properties.
	 */
	public $defaultProperties = array();

	private $_description;
	private $_keywords;
	private $_properties = array();

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		$behavior = $this->controller->asa('seo');

		if ($behavior !== null && $behavior->metaDescription !== null)
			$this->_description = $behavior->metaDescription;
		else if ($this->defaultDescription !== null)
			$this->_description = $this->defaultDescription;

		if ($behavior !== null && $behavior->metaKeywords !== null)
			$this->_keywords = $behavior->metaKeywords;
		else if ($this->defaultKeywords !== null)
			$this->_keywords = $this->defaultKeywords;

		if ($behavior !== null)
			$this->_properties = CMap::mergeArray($behavior->metaProperties, $this->defaultProperties);
		else
			$this->_properties = $this->defaultProperties;
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		$this->renderContent();
	}

	/**
	 * Renders the widget content.
	 */
	protected function renderContent()
	{
		$this->renderTitle();

		foreach ($this->httpEquivs as $name => $content)
			echo '<meta http-equiv="'.$name.'" content="'.$content.'" />',"\r\n";

		if ($this->_description !== null)
			echo CHtml::metaTag($this->_description, 'description'),"\r\n";

		if ($this->defaultKeywords !== null)
			echo CHtml::metaTag($this->_keywords, 'keywords'),"\r\n";

		foreach ($this->_properties as $name => $content)
			echo '<meta property="'.$name.'" content="'.$content.'" />',"\r\n"; // we can't use Yii's method for this.
	}
	
	/**
	 * Renders the page title.
	 */
	protected function renderTitle()
	{
		if (!$this->enableTitle)
			return;

		$title = array();
		$class = 'ext.seo.widgets.SeoTitle';

		if (is_string($this->title))
			$class = $this->title;
		else if (is_array($this->title))
		{
			$title = $this->title;
			if (isset($pager['class']))
			{
				$class = $title['class'];
				unset($title['class']);
			}
		}

		$this->widget($class, $title);
	}
}
