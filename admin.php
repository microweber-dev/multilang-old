<?php only_admin_access(); ?>

<?php require isset($params['backend']) ? 'admin_backend.php' : 'admin_live_edit.php'; ?>

<script type="text/javascript" src="<?php echo $config['url_to_module']; ?>langs.js"></script>
<script type="text/javascript">mw.require('options.js');</script>
<script type="text/javascript">
function reload_after_save() {
  mw.reload_module("#<?php echo $params['id']; ?>");
  mw.reload_module_parent("#<?php echo $params['id']; ?>");
  mw.notification.success("<?php _e('Settings are saved!'); ?>");
}

$(document).ready(function() {
mw.options.form('#<?php echo $params['id']; ?>', function() {
 mw.notification.success("<?php _e("Settings are saved!"); ?>");
});

$('#default_lang').on('change', function() {
  $.post('/api/multilang_set_default', { lang: $(this).val() }, reload_after_save);
});

$('#add_lang').click(function(e) {
  e.preventDefault();
  var lang = $('#site_langs').val();
  if(lang && typeof(MULTILANG_LOCALES[lang]) !== 'undefined') {
    $.post('/api/multilang_add', { 'lang': lang }, reload_after_save);
  }
});

$('.remove_lang').click(function(e) {
  e.preventDefault();
  var lang = $(this).data('lang');
  if(lang && confirm("<?php _e('Are you sure you want to remove this language?'); ?>"))
  $.post('/api/multilang_remove', { 'lang': lang }, reload_after_save);
});

for(var lk in MULTILANG_LOCALES) {
  if(mw.ml_locales.indexOf(lk) >= 0) continue;
  $('#site_langs').append($('<option></option>').val(lk).text(MULTILANG_LOCALES[lk]));
}
});

mw.ml_locales = ['<?php echo implode('\',\'', multilang_locales()); ?>'];
</script>
