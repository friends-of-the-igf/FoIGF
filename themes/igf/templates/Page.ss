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
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "0c00c82d-56a9-4266-9c80-1c3436cc413a", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

	<!-- Bootstrap -->
	<!--<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-button.js"></script>-->
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-collapse.js"></script>
	<!--<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-dropdown.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-tab.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-tooltip.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-popover.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-transition.js"></script>
	<script type="text/javascript" src="$ThemeDir/thirdparty/bootstrap-typeahead.js"></script>-->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-44143735-1', 'friendsoftheigf.org');
  ga('send', 'pageview');

</script>

</head>
<body id="$ClassName">


	<!-- Facebook Widget -->
	<div id="fb-root"></div>
	<script>
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

	<!-- Twitter Widget -->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


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
