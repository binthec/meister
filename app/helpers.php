<?php

function isActiveUrl($url, $string = 'active') {
	return \Illuminate\Support\Facades\Request::is($url) ? $string : '';
}
