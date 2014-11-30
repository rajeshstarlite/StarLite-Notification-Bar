<?php
/**
 * @version        $Id: plg_system_notificationbar.php 2013-07-11 StarLite $
 * @package        StarLite Notification Bar
 * @copyright      Copyright (C) 2013 starliteweb.com All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');


class  plgSystemSlNotificationBar extends JPlugin
{
    var $pluginLivePath;

    function plgSystemSlNotificationBar(& $subject, $config)
    {
        parent::__construct($subject, $config);


        if (version_compare(JVERSION, '1.6.0', 'ge')) {
            $this->pluginLivePath = JURI::root(true) . "/plugins/system/slnotificationbar/slnotificationbar";
        } else {
            $this->pluginLivePath = JURI::root(true) . "/plugins/system/slnotificationbar";
        }
    }


    function onAfterDispatch(){
        $app = JFactory::getApplication();

        if ($app->isAdmin()) return;

        //Added support to show slnotificationbar based on menu items for joomla 1.6 to 2.5
        if (version_compare(JVERSION, '1.6.0', 'ge')) {
            $showinmenu_items = $this->params->get('showinmenu_items',array());

            $Itemid = JRequest::getVar('Itemid');
            if(!in_array($Itemid,$showinmenu_items)){
                return;
            }
        }

        $doc = JFactory::getDocument();

        $include_jquery = $this->params->get('include_jquery', 1);
        if ($include_jquery) {
            $doc->addScript($this->pluginLivePath . "/js/jquery-1.8.2.min.js");
        }

        $include_jquery_ui = $this->params->get('include_jquery_ui',1);
        if ($include_jquery_ui) {
            $doc->addScript($this->pluginLivePath . "/js/jquery-ui-1.8.24.min.js");
        }

        $doc->addStyleSheet($this->pluginLivePath . "/css/bar.css");

        $display_position = $this->params->get('display_position', 'top');

        $SLstyle = '.SLNotificationBar {
                    background-color: '.$this->params->get('backgroundcolor', '#DB5903').' ;
                    color:'.$this->params->get('textcolor', '#FFFFFF').' ;
                    font-size: '.$this->params->get('fontsize', '12').'px;
                    }
                    .SLRibbon {
                     background:'.$this->params->get('backgroundcolor', '#DB5903').' ;
                    }.SLRibbon:hover {
                     background:'.$this->params->get('backgroundcolor', '#DB5903'). ' url('.$this->pluginLivePath.'/img/shine.png) ;
                    }';

        if($display_position=='top'){
            $SLstyle .= '.SLNotificationBar {
                        border-bottom:3px solid #FFF;
                        position:relative;
                        }
                        .SLRibbon {
                        position:absolute;
                        top:0px;
                        border-top:none;
                        }
                        .SLup{
                        top:-50px;
                        }
                       ';
        }else{
            $SLstyle .= '.SLNotificationBar {
                        border-top:3px solid #FFF;
                        position:fixed;
	                    bottom:0;
	                    }
                        .SLRibbon {
                        position:fixed;
	                    bottom:0px;
	                    border-bottom:none;
                        }
                        .SLup{
                        bottom:-50px;
                        }
                        ';
        }

        $doc->addStyleDeclaration( $SLstyle );

        $replacement = '<div class="SLNotificationBar">';
        $replacement .= '<span class="SLhelloinner">';
        $replacement .= '<p class="SLtext">'.$this->params->get('notificationmessage').'</p>';
        $replacement .= '<p class="SLTrigger SLdownarrow">';
        $replacement .= '<img src="' . $this->pluginLivePath . '/img/arrow-up.png" class="SLarrow" alt="Arrow Up"/>';
        $replacement .= '</p>';
        $replacement .= '</span>';
        $replacement .= '</div>';
        $replacement .= '<span class="SLRibbon SLTrigger"><img src="' . $this->pluginLivePath . '/img/arrow-down.png" class="SLarrow" alt="Arrow Down"></span>';

		//replacement of white spaces in HTML
        //bug of previous version
        $replacement = preg_replace('!\s+!smi', ' ', addslashes($replacement));
		
        $noConflict = $this->params->get('jquery_conflict', 1);

        if ($noConflict) {
            $slconfig = "jQuery.noConflict();
                          jQuery(document).ready(function() {
                          jQuery('body').prepend('".$replacement."');
                            jQuery('.SLRibbon').delay(1000).fadeIn(400).addClass('SLup', 600);
                            jQuery('.SLNotificationBar').hide().delay(2500).slideDown(300);
                            jQuery('.SLTrigger').click(function(){
                            jQuery('.SLRibbon').toggleClass('SLup', 300);
                            jQuery('.SLNotificationBar').slideToggle();
                            });
			              });";
        } else {
            $slconfig = "$(document).ready(function() {
                            $('body').prepend('".$replacement."');
                            $('.SLRibbon').delay(1000).fadeIn(400).addClass('SLup', 600);
                            $('.SLNotificationBar').hide().delay(2500).slideDown(300);
                            $('.SLTrigger').click(function(){
                            $('.SLRibbon').toggleClass('SLup', 300);
                            $('.SLNotificationBar').slideToggle();
                            });
	                      });";
        }

        $doc->addScriptDeclaration($slconfig);

    }

    function onAfterRender()
    {

    }

}