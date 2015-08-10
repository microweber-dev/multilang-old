<?php

/*

type: layout

name: Flags

description: Flags Lang List

*/

?>
<?php if (is_array($data)): ?>

<ul class="mw-language mw-language-flags-selector">
  <?php
  foreach($data as $lang): ?>
  <li class="mw-language-flag-item <?php if($lang == App::getLocale()) echo 'selected'; ?>"><a href="javascript:mw.change_language('<?php echo $lang; ?>');"><img src="<?php print $config['url_to_module'] ?>flags/<?php echo $lang; ?>.png" alt="<?php print multilang_locale_name($lang) ?>" class="mw-lang-selector-flag" /></a></li>
  <?php endforeach; ?>
</ul>
<?php else : ?>
<?php endif; ?>
