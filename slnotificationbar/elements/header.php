<?php
/**
 * @package        StarLite Notification Bar
 * @copyright      Copyright (C) 2012 - 2016 starliteweb.com All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if(version_compare(JVERSION,'1.6.0','ge')) {
	jimport('joomla.form.formfield');
	class JFormFieldHeader extends JFormField {

		var	$type = 'header';

		function getInput(){

            $document = JFactory::getDocument();
            $document->addStyleDeclaration('
			.SLHeaderClr { clear:both; height:0; line-height:0; border:none; float:none; background:none; padding:0; margin:0; }
			.SLHeaderContainer,.SLHeaderContainer15 { clear:both; font-weight:bold; font-size:12px; color:#fff; margin:12px 0 4px; padding:0; background:#333333; float:left; width:100%; }
			.SLHeaderContent { padding:6px 8px; }
		');
            if(version_compare(JVERSION,'1.6.0','ge')) {
                return '<div class="SLHeaderContainer"><div class="SLHeaderContent">'.JText::_($this->value).'</div><div class="SLHeaderClr"></div></div>';
            } else {
                return '<div class="SLHeaderContainer15"><div class="SLHeaderContent">'.JText::_($this->value).'</div><div class="SLHeaderClr"></div></div>';
            }
		}

		function getLabel(){
			return '';
		}

	}
}
