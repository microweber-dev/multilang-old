<?php

/*

type: layout

name: Default

description: Default Lang List

*/

?>
<?php if (is_array($data)): ?>

<select class="mw-ui-field mw-language <?php print $class; ?>" onchange="mw.change_language($(this).val());">
  <?php
  foreach($data as $lang): ?>
  <option value="<?php echo $lang; ?>" <?php if($lang == App::getLocale()) echo 'selected'; ?>><?php print multilang_locale_name($lang) ?></option>
  <?php endforeach; ?>
</select>
<?php else : ?>
<?php endif; ?>
