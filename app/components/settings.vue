<template>

    <div class="uk-form uk-form-horizontal">

        <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
            <div data-uk-margin>

                <h2 class="uk-margin-remove">{{ 'Mailchimp Widget Settings' | trans }}</h2>

            </div>
            <div data-uk-margin>

                <button class="uk-button uk-modal-close">{{ 'Close' | trans }}</button>

                <button class="uk-button uk-button-primary uk-margin-small-left" @click="save">{{ 'Save' | trans }}</button>

            </div>
        </div>

        <div class="uk-form-row">
            <label for="form-api_key" class="uk-form-label">{{ 'Mailchimp API key' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-api_key" class="uk-form-width-large" type="text" name="title" v-model="package.config.api_key" v-validate:required>
                <p class="uk-form-help-block uk-text-danger" v-show="form.title.invalid">{{ 'API key cannot be blank.' | trans }}</p>
            </div>
        </div>


    </div>

</template>

<script>

    module.exports = {

        props: ['package'],

        settings: true,

        methods: {

            save() {
                this.$http.post('admin/system/settings/config', {
                        name: 'bixie/mailchimp',
                        config: this.package.config
                    })
                    .then(() => this.$notify('Settings saved.', ''),
                        res => this.$notify(res.data, 'danger'));
            }

        }

    };

    window.Extensions.components['settings-mailchimp'] = module.exports;

</script>
