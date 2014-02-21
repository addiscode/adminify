<div id="container">
<h2 class="company-name">Attractions</h2>

<div></div>
    <?php
        $margin_top = 0;
        foreach($attractions as $attraction) {
        $margin_top = ($margin_top == 0)? $margin_top += 13: $margin_top += 213;
        ?>
    
        <div>
            <div style="width: 200px; text-align: right;float:left">
                <h3 style="width: 100px; float: right; font-family: 'Ropa Sans', sans-serif; color: #555555"><?= $attraction['title'] ?></h3>
                <p style="clear:both; font-family: Arial; color: #666666; font-size: 11px;"><?= $attraction['content'] ?></p>
            </div>
            <img src="<?= base_url()?>/public/images/<?= $attraction['img'] ?>" height="200px" style="float:left;margin:13px 0 0 13px;"/>
            <div style="position: absolute; background-color:#b20000; height: 200px; width: 135px;margin-left: 675px;margin-top:<?= $margin_top ?>px; ">
                <p style="margin-top:150px;padding-right:16px;width:40px;float:right;font-family: 'Ropa Sans', Arial; color: white;font-weight: bolder">READ MORE</p>
            </div>
        </div>
    <? } ?>
    
</div>
