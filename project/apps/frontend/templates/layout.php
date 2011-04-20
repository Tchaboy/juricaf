<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <div class="site">
   <div>
   <a href="<?php echo url_for('@recherche'); ?>"><img src="/images/juricaf.png" alt="Juricaf" /></a>
   </div>
   <?php if ($sf_user->hasFlash('notice')):?>
   <div class="flash notice"><?php echo $sf_user->getFlash('notice'); ?></div>
   <?php endif; ?>
   <?php if ($sf_user->hasFlash('error')):?>
   <div class="flash error"><?php echo $sf_user->getFlash('error'); ?></div>
   <?php endif; ?>
    <?php echo $sf_content ?>
    </div>
  </body>
</html>
