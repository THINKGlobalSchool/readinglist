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

.books-no-results {
	width: 80px;
	margin-right: auto;
	margin-left: auto;
}

#fancybox-wrap {
	top: 100px !important;
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

.book-review-comments {

}

.book-review-comments h3 {
	background-color: #EEEEEE;
	border-radius: 5px 5px 0 0;
	color: #91131E;
	display: block;
	float: right;
	font-size: 90%;
	margin-top: 5px;
	padding: 1px 7px;
	width: auto;
	text-transform: none !important;
	font-family: inherit !important;
}

.book-review-comments .elgg-list {
	border-top: medium none !important;
}

.book-review-comments .elgg-list > li {
	background: #EEEEEE;
	border-bottom: none !important;
	padding: 4px;
	margin-bottom: 2px;
}

.book-review-comments .elgg-list > li:first-child {
	border-radius: 5px 0 0 0;
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

.elgg-menu-item-rating {
	height: 30px;
	margin-right: 30px;
}

.elgg-menu-item-average-rating {
	height: 25px;
	margin-right: 5px;
}

.readinglist-listing-control {
	float: right;
	margin-top: -60px;
}

.readinglist-add-button, .readinglist-remove-button {
	font-size: 90%;
	height: 17px;
	position: relative;
	width: 100px;
}

.readinglist-add-button span.readinglist-button-text, .readinglist-remove-button span.readinglist-button-text {
	position: absolute;
	right: 8px;
}