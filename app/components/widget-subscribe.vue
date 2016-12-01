<template>

    <div class="uk-grid pk-grid-large pk-width-sidebar-large" data-uk-grid-margin>
        <div class="pk-width-content uk-form-horizontal">

            <div class="uk-form-row">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <div class="uk-form-controls">
                    <input id="form-title" class="uk-form-width-large" type="text" name="title" v-model="widget.title" v-validate:required>
                    <p class="uk-form-help-block uk-text-danger" v-show="form.title.invalid">{{ 'Title cannot be blank.' | trans }}</p>
                </div>
            </div>

            <div v-if="!widget.data.list_id" class="uk-alert uk-alert-warning">
                {{ 'Make sure to select a list in the List tab!' | trans }}
            </div>

            <div class="uk-form-row">
                <label for="form-button_text" class="uk-form-label">{{ 'Button text' | trans }}</label>
                <div class="uk-form-controls">
                    <input id="form-button_text" class="uk-form-width-large" type="text" name="title" v-model="widget.data.button_text">
                </div>
            </div>

            <div class="uk-form-row">
                <span class="uk-form-label">{{ 'Optional text above form.' | trans }}</span>
                <div class="uk-form-controls">
                    <v-editor :value.sync="widget.data.text" :options="{markdown : widget.data.markdown}"></v-editor>
                    <p>
                        <label><input type="checkbox" v-model="widget.data.markdown"> {{ 'Enable Markdown' | trans }}</label>
                    </p>
                </div>
            </div>

        </div>
        <div class="pk-width-sidebar">

            <partial name="settings"></partial>

        </div>
    </div>

</template>

<script>


    module.exports = {

        section: {
            label: 'Settings'
        },

        replace: false,

        props: ['widget', 'config', 'form'],

        created() {
            this.$options.partials = this.$parent.$options.partials;
            this.$set('widget.data', _.merge({
                view: 'subscribe',
                button_text: 'Subscribe',
                text: '',
                markdown: false
            }, this.widget.data));
        }

    };

    window.Widgets.components['bixie-mailchimp-subscribe:list'] = require('./widget-subscribe-list.vue');

    window.Widgets.components['bixie-mailchimp-subscribe:settings'] = module.exports;

</script>
