<?php
/**
 * Reading List CSS
 *
 * @package ReadingList
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
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

.book-listing {
	margin: 4px;
	padding: 10px;
	width: 372px;
	height: auto;
	-webkit-box-shadow: 0px 0px 6px #999;
	-moz-box-shadow: 0px 0px 6px #999;
	box-shadow: 0px 0px 6px #999;
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
}

.book-full-view .book-thumbnail {
	float: left;
	margin-right: 10px;
}

.book-listing .book-thumbnail {
	float: left;
	margin-right: 6px;
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

#fancybox-wrap {
	top: 100px !important;
}

/** BOOK RATING INPUT/OUTPUT **/
.bookrating-input, .bookrating-output {
	margin-top: 5px;
}

/**  END BOOK RATING  **/