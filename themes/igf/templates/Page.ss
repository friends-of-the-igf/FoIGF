<!DOCTYPE html>

<!--[if !IE]><!-->
<html lang="$ContentLocale">
<!--<![endif]-->
<!--[if IE 6 ]><html lang="$ContentLocale" class="ie ie6"><![endif]-->
<!--[if IE 7 ]><html lang="$ContentLocale" class="ie ie7"><![endif]-->
<!--[if IE 8 ]><html lang="$ContentLocale" class="ie ie8"><![endif]-->
<head>
	<% base_tag %>
	<title><% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	$MetaTags(false)
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<% require themedCSS('bootstrap') %>
	<% require themedCSS('responsive') %>
	<% require themedCSS('layout') %>
	<link rel="shortcut icon" href="$ThemeDir/images/favicon.ico" />
	<script type="text/javascript" src="$ThemeDir/thirdparty/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="$ThemeDir/javascript/script.js"></script>

	<!-- Bootstrap -->
	<!--<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-button.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-collapse.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-dropdown.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-tab.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-tooltip.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-popover.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-transition.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-typeahead.js"></script>-->

</head>
<body id="$ClassName">
	<div id="wrap">
		<% include Navigation %>
		<% include SearchBar %>
		<% include Header %>
	    <div id="contentWrap" class="container">
			$Layout
	    </div>
	</div>
    <% include Footer %>
</body>
</html>
