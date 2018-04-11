<?php

/**
 * SeoControllerBehavior class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class SeoControllerBehavior extends CBehavior {

    /**
     * @property string the page meta description.
     */
    public $metaDescription;

    /**
     * @property string the page meta keywords.
     */
    public $metaKeywords;

    /**
     * @property array the page meta properties.
     */
    public $metaProperties = array();
    public $httpEquivs = array(); 
    /**
     * Adds a meta property to the current page.
     * @param string $name the property name
     * @param string $content the property content
     */
    public function addMetaProperty($name, $content) {
        $this->metaProperties[$name] = $content;
    }

}