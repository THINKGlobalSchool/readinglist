<?php
/**
 * Reading List CSS
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */
?>

.book-search-table {
	width: 100%;
}

.book-search-table td.book-search-left {
	width: 90%;
}

.book-search-table td.book-search-right {
	padding-top: 2px;
	width: 10%;
}

.book-search-table td.book-search-right input {
	float: right;
}

.book-save-input {

}

#book-form-hidden {
	display: none;
}

.book-listing {
	margin: 4px;
	padding: 10px;
	width: 372px;
	height: auto;
	-webkit-box-shadow: 0px 0px 6px #999;
	-moz-box-shadow: 0px 0px 6px #999;
	box-shadow: 0px 0px 6px #999;
}

.book > .elgg-image-block > .elgg-body > .elgg-subtext {
	width: 50%;
}

#books-search-results {
	width: 400px;
	overflow: hidden;
}

.book-listing:nth-child(even) {
	background: #FFF;
}

.book-listing:nth-child(odd) {
	background: #FFF;
}

.book-listing .book-title {
	font-size: 90%;
	font-weight: bold;
	text-transform: uppercase;
}

.book-listing .book-authors {
	color: #222;
	font-size: 90%;
}

.book-thumbnail {
	background: none repeat scroll 0 0 #FFFFFF;
	border: 1px solid #999;
	padding: 3px;
	display: inline-block;
}

.book-river-thumbnail {
	float: left;
	margin-right: 5px;
}

.book-sidebar {
	border-radius: 10px;
	background: #FEFEFE;
	margin-top: 8px;
	margin-bottom: 8px;
	padding-top: 4px;
	padding-bottom: 10px;
	padding-left: 10px;
	padding-right: 10px;
	-webkit-box-shadow: inset 0px 0px 2px 1px #999999;
	-moz-box-shadow: inset 0px 0px 2px 1px #999999;
	box-shadow: inset 0px 0px 2px 1px #999999;
}

.book-sidebar-title {
	display: block;
	text-transform: uppercase;
	margin-bottom: 4px;
}

.book-sidebar-module .elgg-list {
	border-top: 0;
}

.book-sidebar img {
	max-height: 45px;
}

.book-sidebar-module .elgg-list > li {
	border-bottom: 0;
}

.author-subtext {
	color: #999999;
	font-style: italic;
	line-height: 1.2em;
}

.book-full-view .book-thumbnail {
	float: left;
	margin-right: 10px;
}

.book-listing .book-thumbnail {
	float: left;
	margin-right: 6px;
}

.elgg-list-entity .book-thumbnail img, .book-listing .book-thumbnail img {
	max-width: 50px;
}

.book-select-input {
	margin-top: 10px;
}

.book-select-input input {
	float: right;
}

.trigger-book-results {
	display: none;
}

.books-no-results {
	width: 80px;
	margin-right: auto;
	margin-left: auto;
}

.bookrating-input, .bookrating-output {
	margin-top: 5px;
}

.book-reviews {
	margin-top: 25px;
}

.book-review-add {
	margin-top: 25px;
	padding-top: 10px;
}



#review-form-container {
	display: none;
}

#rating-error {
	color: red;
	font-weight: bold;
	margin-left: 25px;
}

.book-completed-container {
	text-align: right;
}

.book-full-status-container .book-completed-container {
    clear: both;
    margin-left: 10px;
    text-align: right;
}

.book-full-status-container {
    clear: both;
    display: block;
    float: right;
    margin-left: 10px;
    margin-top: 10px;
    text-align: right;
}

.book-full-status-container .complete-date {
	padding-top: 6px;
	padding-left: 6px;
}

.book-full-status-container .book-reading-status {
	display: inline;
}

.book-full-button-container {
	float: right;
}

.book-date-complete {
	width: 225px;
	margin-top: 7px;
}

.readinglist-listing-control label {
	position: relative;
	top: -3px;
}

.readinglist-listing-control .book-reading-status {
	position: relative;
	top: -3px;
}

.review-comment-button {
	font-size: 85%;
	float: right;
}

.review-comment-button .elgg-icon {
	margin-right: 5px;
	top: 3px;
}

.whos-reading-show {
	color: #555555;
	font-size: 90%;
	font-style: italic;
}

.elgg-menu-item-rating {
	height: 30px;
	margin-right: 30px;
}

.elgg-menu-item-book-rating {
	height: 25px;
	margin-right: 5px;
}

.readinglist-listing-control {
	float: right;
	margin-top: -60px;
}

#readinglist-books-readinglist-module .readinglist-listing-control {
	float: none;
	margin-top: 0;
	margin-bottom: 10px;
}

.book-regular-listing.readinglist-listing-control {
	margin-top: -50px;
}

.readinglist-button {
	position: relative;
	  font-size: 95%;
  height: 19px;
  position: relative;
  width: 110px;
}

.readinglist-button .elgg-icon {
	margin-left: 0;
	margin-top: 1px;
}

.readinglist-remove-button, .group-readinglist-remove-button {
	color: white;
	text-decoration: none;
}

.readinglist-remove-button:hover, .group-readinglist-remove-button:hover {}


.readinglist-button span.readinglist-button-text {
	position: absolute;
	right: 10px;
}

.average-bookrating-tooltip {
	height: 30px;
}

/** FILTER/SORT MENU **/

.elgg-menu-readinglist-filter {

}

.elgg-menu-readinglist-filter li label {
	margin-right: 10px;
	font-size: 90%;
	text-transform: uppercase;
}

.elgg-menu-readinglist-filter li.elgg-menu-item-readinglist-order {
	margin-right: 10px;
	font-size: 90%;
	text-transform: uppercase;
	font-weight: bold;
}

.elgg-menu-readinglist-filter li select {
	margin-right: 15px;
}

.elgg-menu-readinglist-filter li a {
	color: #666;
}

.elgg-menu-readinglist-filter li.elgg-state-selected a {
	font-weight: bold;
	color: inherit;
}


/** END OF SORT MENU **/