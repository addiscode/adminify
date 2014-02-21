<script type="text/javascript">
            $(function(){
                        $("#logInForm").submit(function(){
                                    $.post('<?= base_url()?>index.php/welcome/authenticate',$("#logInForm").serialize(), 
                                           function(data){
                                                var isValid = data;
                                                if(isValid == "EMPTY") {
                                                            $("#alert").html("Both of the fields are required");
                                                            $("#alert").show();
                                                }
                                                if(isValid == "false"){
                                                            $("#alert").html("Invalid email & password combination");
                                                            $("#alert").show();
                                                }
                                                if(isValid == "true") {
                                                            window.location = "<?= base_url() ?>";
                                                }
                                    },
                                    'text');
                                    return false;
                        });
            });
</script>
<link href="<?= base_url() ?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<div class="span5 offset3">
            
            <?php if($this->session->flashdata("message")) {?>
            <div id="alert" class="alert alert-information">
                        <?= $this->session->flashdata("message") ?>
            </div>            
            <? } ?>
            <form  method="post" id="logInForm" class="form-horizontal">
                    <legend>Admin Log in</legend>
                    <div id="alert" class="alert alert-error" style="display:none"></div>
                    <div class="control-group">
                      <label class="control-label" for="inputEmail">Email</label>
                      <div class="controls">
                        <input type="text" name="email" id="inputEmail" placeholder="Email">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="inputPassword">Password</label>
                      <div class="controls">
                        <input type="password" name="password" id="inputPassword" placeholder="Password">
                      </div>
                    </div>
                    <div class="control-group">
                      <div class="controls">
                        <label class="checkbox">
                          <?php echo anchor("welcome/recovery","Forget my password"); ?>
                        </label>
                          <input type="submit" class="btn" name="submit" value="Sign in" />
                      </div>
                    </div>
                </form>
</div>