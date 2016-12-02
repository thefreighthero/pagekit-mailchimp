<?php
/**
 * @var \Pagekit\View\View $view
 * @var \Pagekit\Widget\Model\Widget $widget
 * @var string $id
 * @var string $list_id
 * @var string $text
 * @var string $mergefields_string
 */

$view->script('widget-mailchimp-subscribe', 'bixie/mailchimp:app/bundle/widget-mailchimp-subscribe.js', ['vue']);

?>

<form id="<?=$id?>" data-list_id="<?=$list_id?>" class="uk-form" v-cloak>

	<?php if (!empty($text)) : ?>
		<div class="uk-clearfix"><?php echo $text; ?></div>
	<?php endif; ?>


	<div v-for="mergefield in mergefields | filterBy true in 'widget_show' | orderBy 'widget_order'" class="uk-form-row">
		<label class="uk-form-label">{{ mergefield.label }}</label>
		<div class="uk-form-controls">

			<template v-if="mergefield.field_type == 'text'">
				<input type="text" v-model="mergefield.value" class="uk-width-1-1"/>
			</template>

			<template v-if="mergefield.field_type == 'email'">
				<input type="email" v-model="mergefield.value" class="uk-width-1-1"/>
			</template>

			<template v-if="mergefield.field_type == 'radio'">
				<p v-for="choice in mergefield.choices" class="uk-form-controls-condensed">
					<label><input type="radio" v-model="mergefield.value" :value="choice"/> {{ choice }}</label>
				</p>
			</template>

			<template v-if="mergefield.field_type == 'dropdown'">
				<select v-model="mergefield.value" class="uk-width-1-1">
					<option v-for="choice in mergefield.choices" :value="choice">{{ choice }}</option>
				</select>
			</template>

		</div>
	</div>

	<p class="uk-text-right">
		<button type="button" @click="submit" class="uk-button uk-button-primary">
			<i class="uk-icon-envelope-o uk-margin-small-right"></i><?= $widget->get('button_text', 'Subscribe') ?>
		</button>
	</p>

	<div v-if="loading || result" class="uk-alert" :class="{
				'uk-alert-success': result == 'success',
				'uk-alert-warning': result == 'warning',
				'uk-alert-danger': result == 'fail'
			}">
		<div v-show="loading" class="uk-text-center"><i class="uk-icon-circle-o-notch uk-icon-spin"></i></div>
		{{ message }}
	</div>
</form>
<script>
	Vue.ready(function () {
		var widget = new Vue(window.MailchimpSubscribe).$mount('#<?=$id?>');
		widget.setMergefields(<?=$mergefields_string?>);
	});
</script>
