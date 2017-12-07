<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <title>Authorization Required</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="Tpl/assets/css/cerulean.min.css" rel="stylesheet">
  <link href="Tpl/assets/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="Tpl/assets/css/font-awesome.min.css" rel="stylesheet">
  <link href="Tpl/assets/js/google-code-prettify/prettify.css" rel="stylesheet">
  <link href="Tpl/assets/css/docs.css?v=11" rel="stylesheet">
  </head>

  <body>
          
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span12 center" style="padding:20px">
          <h1 id="top_title"></h1>
        </div>
      </div>
      <div id="loginpage" class="row-fluid">
        <div style="width:560px;margin:0 auto;">
          <form class="form-horizontal well" id="form_login" action="register.php" method="post">
            <h1 class="center">Authorization Required</h1>
            <hr style="border-bottom-color: #E5E5E5;">
            <div id="uid_ctl" class="control-group">
              <label class="control-label" for="uid">Real Name:</label>
              <div class="controls">
                <input autofocus type="text" id="uid" name="uid" placeholder="Real name">
              </div>
            </div>
            <div id="pwd_ctl" class="control-group">
                <label class="control-label" for="pwd">Password:</label>
                <div class="controls">
                  <input id="pwd" name="pwd" type="password" placeholder="Password">
                </div>
            </div>
            <div class="control-group">
              <div class="controls">
                <input id="signin" type="submit" value="Sign in" class="btn btn-primary">
                <input id="ret_url" name="url" value="index.php" type="hidden">
          </form>
                <input class="btn btn-danger" type="submit" onClick="return switch_page();" value="Sign up" />

              </div>
            </div>
            
        </div>
      </div>

      <div id="regpage" class="hide row-fluid">
        <div class="span6 offset3">
          <form class="form-horizontal well" id="form_profile" action="#" method="post">
            <input type="hidden" value="reg" name="type">
            <fieldset>
              <div class="control-group" id="userid_ctl">
                <label class="control-label">Real Name</label>
                <div class="controls">
                  <input placeholder="Real name" class="input-xlarge" type="text" name="name" id="input_name">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input_nick">Nick Name</label>
                <div class="controls">
                  <input placeholder="Or your signature" class="input-xlarge" type="text" name="nick" id="input_nick">
                </div>
              </div>
              <div class="control-group" id="newpwd_ctl">
                <label class="control-label" for="input_password">Password</label>
                <div class="controls">
                  <input placeholder="A safe password" class="input-xlarge" type="password" id="input_password" name="password">
                </div>
              </div>
              <div class="control-group" id="reppwd_ctl">
                <label class="control-label" for="input_reppwd">Repeat Password</label>
                <div class="controls">
                  <input placeholder="Double check" class="input-xlarge" type="password" id="input_reppwd">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input_email">E-mail</label>
                <div class="controls">
                  <input placeholder="name@example.com" class="input-xlarge" type="text" name="mail" id="input_mail">
                </div>
              </div>
			  <div class="control-group">
                <label class="control-label" for="input_school">School</label>
                <div class="controls">
                  <select name="school" id="input_school">
				  <?php
				  $schoolList=array('长郡中学', '长郡双语','麓山国际','长郡滨江','长郡梅溪湖','长郡郡维','湘郡培粹','株洲二中');
				  $year=date('Y');
				  foreach($schoolList as $item)
				  {
					  for($i = 0; $i != 3; ++$i)
					  {
						  $y=$year-$i;
						  echo "<option>{$item}{$y}级</option>";
					  }
				  }
				  ?>
            <option selected="selected">其他</option>
				  </select>
                </div>
              </div>
              <div class="center">
                <span id="save_btn" class="btn btn-primary">Submit</span>
              </div>
              <div class="row-fluid">
                <span id="ajax_result" class="hide span6 offset3 alert alert-error center" style="margin-top:20px"></span>
              </div>
            </fieldset>
          </form>
        </div>
      </div>

      <hr>
      <footer>
        <p>Copyright &copy; 2014 - <?=date('Y')?> Boyin Chen</p>
      </footer>
    </div>
    <script src="Tpl/assets/js/jquery.js"></script>
    <script src="Tpl/assets/js/common.js"></script>
    <script type="text/javascript">
      function switch_page() {
        $('#loginpage').hide();
        $('h1').html('Application Form');
        $('#regpage').show();
        return false;
      }
      $(document).ready(function() {
        $('#save_btn').click(function(){
          var b=false,pwd;
          if(!$.trim($('#input_name').val())) {
            $('#userid_ctl').addClass('error');
            b=true;
          }else{
            $('#userid_ctl').removeClass('error');
          }
          pwd=$('#input_password').val();
          if(pwd!='' && $('#input_reppwd').val()!=pwd){
            b=true;
            $('#newpwd_ctl').addClass('error');
            $('#reppwd_ctl').addClass('error');
          }else{
            $('#newpwd_ctl').removeClass('error');
            $('#reppwd_ctl').removeClass('error');
          }
          if(!b){
            $.ajax({
              type:"POST",
              url:"register.php",
              data:$('#form_profile').serialize(),
              success:function(msg){
                if(/created/.test(msg)){
                  window.alert('Your account request has been submitted.');
                  window.location="index.php";
                }else{
                  $('#ajax_result').html(msg).show();
                }
              }
            });
          }
        });
      });
    </script>
  </body>
</html>
