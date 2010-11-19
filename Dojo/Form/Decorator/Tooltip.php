<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Form
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Dojo_Form_Decorator_DijitContainer */
require_once 'Zend/Dojo/Form/Decorator/DijitContainer.php';

/**
 * Tooltip
 *
 * Render a dijit Tooltip
 *
 * @uses       Zend_Dojo_Form_Decorator_DijitContainer
 * @package    Zend_Dojo
 * @subpackage Form_Decorator
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Tooltip.php 22 2010-04-11 14:44:07Z merizamed $
 */
class Zend_Dojo_Form_Decorator_Tooltip extends Zend_Dojo_Form_Decorator_DijitContainer
{
    /**
     * View helper
     * @var string
     */
    protected $_helper = 'Tooltip';

    /**
     * Show the dijit.Tooltip from title attribute, translates the title attribute if it
     * is available, if the translator is available and if the translator is not disable
     * on the element being rendered.
     *
     * @param string $content
     * @return string
     */
    public function render($content)
    {
        if (null !== ($title = $this->getElement()->getAttrib('title'))) {
            if (null !== ($translator = $this->getElement()->getTranslator())) {
                $title = $translator->translate($title);
            }
        }

        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $dijitParams = $this->getDijitParams();
        $attribs     = array_merge($this->getAttribs(), $this->getOptions());

        $tooltipContent = '';
        if (array_key_exists('title', $attribs)) {
            if ((!array_key_exists('title', $dijitParams) || empty($dijitParams['title']))
                && (!array_key_exists('label', $dijitParams) || empty($dijitParams['label']))) {
                $tooltipContent = $attribs['title'];
            } elseif (array_key_exists('title', $dijitParams) || !empty($dijitParams['title'])) {
                $tooltipContent = $dijitParams['title'];
            } elseif (array_key_exists('label', $dijitParams) || !empty($dijitParams['label'])) {
                $tooltipContent = $dijitParams['label'];
            }
            unset($attribs['title'], $dijitParams['label'], $dijitParams['title']);
        }

        $helper                   = $this->getHelper();
        $elementId                = $element->getId();
        $id                       = $elementId . '-' . $helper;
        $dijitParams['connectId'] = $elementId;

        if ($view->dojo()->hasDijit($id)) {
            trigger_error(sprintf('Duplicate dijit ID detected for id "%s; temporarily generating uniqid"', $id), E_USER_WARNING);
            $base = $id;
            do {
                $id = $base . '-' . uniqid();
            } while ($view->dojo()->hasDijit($id));
        }

        return $content . $view->$helper($id, $tooltipContent, $dijitParams, $attribs);
    }
}
