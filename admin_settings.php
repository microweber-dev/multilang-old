<h3>Multilingual Support</h3>

<div>
	<label class="mw-ui-check">
		<input name="is_multilang" type="checkbox" class="mw_option_field" value="1" option-group="website" <?php if( get_option('is_multilang', 'website') ) echo 'checked'; ?>>
		<span></span>
		<span>This site has multilingual content</span>
	</label>
</div>

<div>
	<label class="mw-ui-check">
		<input name="mutilang_no_fallback" class="mw_option_field" type="checkbox" value="1" option-group="website" <?php if( get_option('mutilang_no_fallback', 'website') ) echo 'checked'; ?>>
		<span></span>
		<span>Don't show the original content if translation is unavailable</span>
	</label>
</div>

<h3>Primary Language</h3>
<select id="default_lang" type="text" class="mw-ui-field mw-language">
<?php foreach(multilang_locales() as $lang): ?>
  <option value="<?php echo $lang; ?>" <?php if($lang == config('app.fallback_locale')) echo 'selected'; ?>></option>
<?php endforeach; ?>
</select>

<h3>Supported Languages</h3>

<table width="100%">
	<tr>
		<td width="45%" valign="top">
			<?php foreach(multilang_locales() as $lang): ?>
			<div class="mw-ui-box mw-ui-box-content">
				<a href="#" class="remove_lang pull-right" data-lang="<?php echo $lang; ?>">
					<span class="mw-icon-close"></span>
				</a>
				<span class="mw-language-tag"><?php echo $lang; ?></span>
			</div>
			<?php endforeach; ?>
		</td>
		<td width="10%">&nbsp;</td>
		<td width="45%" valign="top">
			<div class="mw-ui-box">
				<div class="mw-ui-box-header">
					<span class="mw-icon-plus"></span>
					<span>Add new language</span>
				</div>
				<div class="mw-ui-box-content">
					<select id="site_langs" class="mw-ui-field">
					  <option value="">Select a language</option>
					</select>

					<a href="#" class="mw-ui-btn" id="add_lang">
					  <span class="mw-icon mw-icon-plus"></span>
					  Add selected
					</a>
				</div>
			</div>
		</td>
	</tr>
</table>
