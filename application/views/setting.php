<link href="<?= base_url() ?>public/css/validationEngine.jquery.css" rel="stylesheet">      
<script type="text/javascript" src="<?= base_url() ?>/public/scripts/jquery.validationEngine.js"></script>
<script type="text/javascript" src="<?= base_url() ?>/public/scripts/languages/jquery.validationEngine-en.js"></script>
<script type="text/javascript">
            $(function(){
                        $("#settingForm").validationEngine();
                        //regula.bind();
                        $("#settingForm").submit(function(){
                            return $("#settingForm").validationEngine('validate');
                        });
            });
</script>
<div class="span5 offset3">
            <?php if(isset($error)) { ?>
            <div id="alert"  class="alert alert-error">
                        <?= $error ?>
            </div>
            <? } ?>
            <form  method="post" id="settingForm" class="form-horizontal" action="<?= base_url() ?>index.php/welcome/setting">
                    <legend>Account settings</legend>
                    <div class="control-group">
                      <label class="control-label" for="inputEmail">Email</label>
                      <div class="controls">
                        <input type="text" class="validate[required,custom[email]] input-text"  name="email" id="inputEmail" placeholder="Email">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="inputPassword">Old Password</label>
                      <div class="controls">
                        <input type="password" class="validate[required] input-text" name="old_password" id="inputPassword" placeholder="Password">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="inputPassword">New Password</label>
                      <div class="controls">
                        <input type="password" class="validate[required] input-text" name="password" id="inputPassword" placeholder="Password">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="inputPassword">Comfirm Password</label>
                      <div class="controls">
                        <input type="password" class="validate[required] input-text" name="confirm_password" id="inputPassword" placeholder="Password">
                      </div>
                    </div>
                    <div class="control-group">
                      <div class="controls">
                          <input type="submit" class="btn" name="submit" value="Update profile" />
                      </div>
                    </div>
                </form>
</div>