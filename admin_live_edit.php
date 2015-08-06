<div class="settings-wrapper">
  <div class="module-live-edit-settings"> 
    <script>
  $(document).ready(function(){
 
    mw.tabs({
       tabs:'.tab',
       nav:'.mw-ui-btn-nav a'
    });
});
 </script>
    <div class="mw-ui-box-content">
      <style scoped="scoped">

.tab{
  display: none;
}

</style>
      <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs"> <a href="javascript:;" class="mw-ui-btn active">
        <?php _e("Language settings"); ?>
        </a> <a href="javascript:;" class="mw-ui-btn">
        <?php _e("Skin/Template"); ?>
        </a> </div>
      <div class="tab mw-ui-box mw-ui-box-content" style="display: block">
        <?php require __DIR__.DS.'admin_settings.php'; ?>
        <?php if(isset($params['backend'])): ?>
        <?php $translations = DB::table('translations')->get(); ?>
        <h3> Available Translations
          (<?php echo count($translations); ?>) </h3>
        <table width="100%">
          <thead>
            <tr>
              <th>Language</th>
              <th>Source ID</th>
              <th>Source Type</th>
              <th>Translated Data</th>
            </tr>
          <thead>
            <?php if(count($translations)): ?>
            <?php foreach($translations as $translation): ?>
            <tr>
              <td><?php echo $translation->lang; ?>
                <div class="mw-language-tag"><?php echo $translation->lang; ?></div></td>
              <td align="center"><?php echo $translation->translatable_id; ?></td>
              <td align="center"><?php echo $translation->translatable_type; ?></td>
              <td><pre>
<?php
$json = json_decode($translation->translation);
var_dump((array)$json);
?>
</pre></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <?php endif; ?>
      </div>
      <div class="tab mw-ui-box mw-ui-box-content">
        <module type="admin/modules/templates"  />
      </div>
    </div>
  </div>
</div>
