<?php
$content = collect(get_content())->groupBy('content_type');
$translations = collect(DB::table('translations')->get());
$translatedContent = array();

foreach (multilang_locales() as $lang) {
	$translatedContent[$lang] = $translations
		->where('translatable_type', 'content')
		->where('lang', $lang)
		->lists('translatable_id');
}

?>
<div class="mw-module-admin-wrap">

	<module type="admin/modules/info" />

	<?php require 'admin_settings.php'; ?>


	<h3>
		Available Translations
		(<?php echo $translations->count(); ?>)
	</h3>

	<?php foreach($content as $contentType => $content): ?>
	<h4><?php echo ucfirst( str_plural($contentType) ); ?></h4>

	<table class="mw-ui-table" style="text-align: center;">
	<thead>
		<tr>
			<th width="30%">&nbsp;</th>
			<?php foreach (multilang_locales() as $lang): ?>
			<th class="mw-language-tag"><?php echo $lang; ?></th>
			<?php endforeach; ?>
		</tr>
	<thead>
	<tbody>
		<?php foreach ($content as $item): ?>
		<tr>
			<td align="right">
				<a href="<?php echo $item['url']; ?>"  target="_blank">
					<?php echo str_limit($item['title'], 32); ?>
				</a>
			</td>
			<?php foreach (multilang_locales() as $lang): ?>
			<td>
				<?php if($lang == config('app.fallback_locale') || in_array($item['id'], $translatedContent[$lang])): ?>
				<span class="mw-icon mw-icon-check"></span>
				<?php endif; ?>
			</td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
	</table>
	<?php endforeach; ?>
</div>
