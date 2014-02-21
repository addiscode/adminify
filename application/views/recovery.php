<link href="<?= base_url() ?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<div class="span5 offset3">
            <?php if(isset($error)) { ?>
            <div id="alert"  class="alert alert-error">
                        <?= $error ?>
            </div>
            <? } ?>
            <form  method="post" id="logInForm" class="form-horizontal">
                    <legend>Password recovery wizard</legend>
                    <div class="control-group">
                      <label class="control-label" for="inputEmail">Email</label>
                      <div class="controls">
                        <input type="text" name="email" id="inputEmail" placeholder="Email">
                      </div>
                    </div>
                    <div class="control-group">
                      <div class="controls">
                          <input type="submit" class="btn" name="submit" value="Recover password" />
                      </div>
                    </div>
                </form>
</div>