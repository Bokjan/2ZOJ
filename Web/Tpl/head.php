<head>
  <meta charset="utf-8">
  <title><?php echo $title?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?=C('APP_URL');?>Tpl/assets/css/cerulean.min.css" rel="stylesheet">
  <link href="<?=C('APP_URL');?>Tpl/assets/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="<?=C('APP_URL');?>Tpl/assets/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?=C('APP_URL');?>Tpl/assets/js/google-code-prettify/prettify.css" rel="stylesheet">
  <link href="<?=C('APP_URL');?>Tpl/assets/css/docs.css?v=11" rel="stylesheet">
  <script src="<?=C('APP_URL');?>Tpl/assets/js/jquery.js" type="text/javascript"></script>
  <script src="<?=C('APP_URL');?>Tpl/assets/js/bootstrap.min.js" type="text/javascript"></script>
  <!-- MathJax -->
		<script type="text/x-mathjax-config">
			MathJax.Hub.Config({
				showProcessingMessages: false,
				tex2jax: {
					inlineMath: [["$", "$"], ["\\\\(", "\\\\)"]],
					processEscapes:true
				},
				menuSettings: {
					zoom: "Hover"
    			}
			});
		</script>
    <script src="//cdn.bootcss.com/mathjax/2.7.0/MathJax.js" type="text/javascript"></script>
    <script src="//cdn.bootcss.com/mathjax/2.7.0/config/TeX-AMS-MML_SVG.js" type="text/javascript"></script>
</head>
<body>
<div class="navbar" id="navbar_pseude">
  <div class="navbar-inner" style="padding:0"></div>
</div>
<div class="navbar navbar-fixed-top" id="navbar_top">
  <div class="navbar-inner" style="padding:0">
    <div class="container-fluid navbar-padding-fix">
      <a class="brand" href="<?=C('APP_URL');?>"><span class="navbar-hide-text">2ZOJ</span></a>
        <ul class="nav">
          <!--<li><a id="nav_bbs" href="board.php"><i class="icon-bullhorn"></i><span class="navbar-hide-text"> Board</span></a></li>-->
          <li><a id="nav_set" href="<?=U('problem/viewlist');?>"><i class="icon-th-list"></i><span class="navbar-hide-text"> Problem</span></a></li>
          <li><a id="nav_prob" href="<?=U('problem/random');?>"><i class="icon-play-circle"></i><span class="navbar-hide-text"> Random</span></a></li>
          <!--<li><a id="nav_con" href="<?=U('contest/overview');?>"><i class="icon-trophy"></i><span class="navbar-hide-text"> Contest</span></a></li>-->
		  <li><a id="nav_con" href="<?=U('task/overview');?>"><i class="icon-tasks"></i><span class="navbar-hide-text"> Task</span></a></li>
		  <li><a id="nav_record" href="<?=U('recent/overview');?>"><i class="icon-rss"></i><span class="navbar-hide-text"> Recent</span></a></li>
          <li><a id="nav_record" href="<?=U('solution/record');?>"><i class="icon-camera"></i><span class="navbar-hide-text"> Record</span></a></li>
          <li><a id="nav_rank" href="<?=U('user/ranklist');?>"><i class="icon-thumbs-up"></i><span class="navbar-hide-text"> Ranklist</span></a></li>
          <li><a id="nav_about" href="<?=U('index/about');?>"><i class="icon-phone"></i><span class="navbar-hide-text"> About</span></a></li>
        </ul>
      <div class="btn-group pull-right">
        <a class="btn dropdown-toggle" data-toggle="dropdown" style="white-space:nowrap" href="#">
          <i class="icon-user"></i>
          <?php
          global $user;
          echo $user->name,'<strong id="notifier"></strong>';
          ?>
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
		  <li><a href="<?=U('user/profile');?>"><i class="icon-github"></i> Profile</a></li>
		  <li><a href="<?=U('user/marked');?>"><i class="icon-star"></i> Marked</a></li>
          <li><a href="<?=U('user/preference');?>"><i class="icon-cogs"></i> Preference</a></li>
			<li class="divider"></li>
		  <li><a><i class="icon-flag"></i> <?=$user->group;?></a></li>
            <li class="divider"></li>
          <li><a id='logoff_btn' href="<?=U('user/logout');?>"><i class="icon-signout"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>


<div class="container-fluid">
