<!-- Main jumbotron for a primary marketing message or call to action -->

<div class="bg"></div>
<div class="jumbotron">
    <?php if (NBCU_BRANDING) { ?>
    <img id="nbcunilogo" src="img/content/NBC_Universal.png" />
	<?php } else { ?>
	<img id="nologo" src="img/content/nologo.png" />
	<?php } ?>
    <h1 id="worldcuptext">
    	Brazil
	    <font color="#ffcb05">2</font><font color="#2879bb">0</font><font color="#0db24c">1</font><font color="#db2033">4</font>
    </h1>
    <p><?php echo WORDING_WELCOME ?></p>
    <p><a class="btn btn-primary btn-lg" role="button" href="forum.php?f=2&t=1">Learn more &raquo;</a></p>
</div>