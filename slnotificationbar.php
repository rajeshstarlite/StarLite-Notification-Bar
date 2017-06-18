<?php
/**
 * @package        StarLite Notification Bar
 * @copyright      Copyright (C) 2012 - 2017 starliteweb.com All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');


class  plgSystemSlNotificationBar extends JPlugin
{
    var $pluginLivePath;

    function __construct( $subject, $config)
    {
        parent::__construct($subject, $config);

        $this->pluginLivePath = JURI::root(true) . "/plugins/system/slnotificationbar";

    }


    function onAfterDispatch(){
        $app = JFactory::getApplication();

        if ($app->isAdmin()) return;


        $showinmenu_items = $this->params->get('showinmenu_items',array());

        $Itemid = JFactory::getApplication()->input->get('Itemid');
        if(!in_array($Itemid,$showinmenu_items)){
            return;
        }

        JHtml::_('jquery.framework');

        $doc = JFactory::getDocument();

        $doc->addStyleSheet($this->pluginLivePath . "/assets/css/bar.css");

        $display_position = $this->params->get('display_position', 'top');

        $SLstyle = '.SLNotificationBar {
                    background-color: '.$this->params->get('backgroundcolor', '#DB5903').' ;
                    color:'.$this->params->get('textcolor', '#FFFFFF').' ;
                    font-size: '.$this->params->get('fontsize', '12').'px;
                    }
                    .SLRibbon {
                     background:'.$this->params->get('backgroundcolor', '#DB5903').' ;
                    }.SLRibbon:hover {
                     background:'.$this->params->get('backgroundcolor', '#DB5903'). ' url('.$this->pluginLivePath.'/assets/img/shine.png) ;
                    }';

        if($display_position=='top'){
            $SLstyle .= '.SLNotificationBar {
                        border-bottom:none;
                        position:fixed;
                        left:0;
                        top:0;
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
	                    left:0;
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
        $replacement .= '<img src="' . $this->pluginLivePath . '/assets/img/arrow-up.png" class="SLarrow" alt="Arrow Up"/>';
        $replacement .= '</p>';
        $replacement .= '</span>';
        $replacement .= '</div>';
        $replacement .= '<span class="SLRibbon SLTrigger"><img src="' . $this->pluginLivePath . '/assets/img/arrow-down.png" class="SLarrow" alt="Arrow Down"></span>';

		//replacement of white spaces in HTML
        //bug of previous version
        $replacement = preg_replace('!\s+!smi', ' ', addslashes($replacement));

        $slconfig = "
                     var slcookie = SLgetCookie(\"slcookie\");
                     if (slcookie == \"\") {
                        SLsetCookie('slcookie', 'open');
                     } 
                     function SLsetCookie(cname, cvalue) {
                        var d = new Date();
                        var exdays = 30;
                        d.setTime(d.getTime() + (exdays*24*60*60*1000));
                        var expires = \"expires=\"+d.toUTCString();
                        document.cookie = cname + \"=\" + cvalue + \"; \" + expires+\";path=/\";
                    }
                    function SLgetCookie(cname) {
                        var name = cname + \"=\";
                        var ca = document.cookie.split(';');
                        for(var i = 0; i < ca.length; i++) {
                            var c = ca[i];
                            while (c.charAt(0) == ' ') {
                                c = c.substring(1);
                            }
                            if (c.indexOf(name) == 0) {
                                return c.substring(name.length, c.length);
                            }
                        }
                        return \"\";
                    }
                    jQuery(document).ready(function() {
                      jQuery('body').prepend('".$replacement."');
                        var getslcookie = SLgetCookie('slcookie');
                        if(getslcookie=='open'){
                            jQuery('.SLRibbon').delay(1000).fadeIn(400).addClass('SLup', 600);
                            jQuery('.SLNotificationBar').hide().delay(2500).slideDown(300);
                        }else{
                            jQuery('.SLNotificationBar').hide();
                            jQuery('.SLRibbon').delay(1000).fadeIn(400);
                        }
                        jQuery('.SLTrigger').click(function(){
                            jQuery('.SLRibbon').toggleClass('SLup', 300);
                            jQuery('.SLNotificationBar').slideToggle(400,'',function(){ 
                                var getslcookie = SLgetCookie('slcookie');
                                if(getslcookie=='open'){ 
                                    var setslcookie = 'close';
                                }else{
                                    var setslcookie = 'open';
                                }
                        SLsetCookie('slcookie', setslcookie);});
                        });
                    });";

        $doc->addScriptDeclaration($slconfig);

    }

}