<?php
/**
 * Star-rating CSS Include
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$css_path = elgg_get_site_url();
$css_path = "{$css_path}mod/readinglist/vendors/starrating/";
?>
/* jQuery.Rating Plugin CSS - http://www.fyneworks.com/jquery/star-rating/ */
div.rating-cancel,div.star-rating{float:left;width:17px;height:15px;text-indent:-999em;cursor:pointer;display:block;background:transparent;overflow:hidden}
div.rating-cancel,div.rating-cancel a{background:url(<?php echo $css_path; ?>delete.gif) no-repeat 0 -16px}
div.star-rating,div.star-rating a{background:url(<?php echo $css_path; ?>star.gif) no-repeat 0 0px}
div.rating-cancel a,div.star-rating a{display:block;width:16px;height:100%;background-position:0 0px;border:0}
div.star-rating-on a{background-position:0 -16px!important}
div.star-rating-hover a{background-position:0 -32px}
/* Read Only CSS */
div.star-rating-readonly a{cursor:default !important}
/* Partial Star CSS */
div.star-rating{background:transparent!important;overflow:hidden!important}
/* END jQuery.Rating Plugin CSS */