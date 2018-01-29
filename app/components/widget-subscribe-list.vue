<template>

    <div>

        <div class="uk-form-row">
            <label for="form-list_id" class="uk-form-label">{{ 'List' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-list_id" name="list_id" v-model="widget.data.list_id" class="uk-form-width-medium" v-validate:required>
                    <option value="">{{ 'Choose list' | trans }}</option>
                    <option v-for="list in lists" :value="list.id">{{ list.name }}</option>
                </select>
                <p class="uk-form-help-block uk-text-danger" v-show="form.list_id.invalid">{{ 'List id cannot be blank.' | trans }}</p>
            </div>
        </div>

        <div class="uk-form-row">
            <span class="uk-form-label">{{ 'Fields' | trans }}</span>
            <div class="uk-form-controls">

                <ul class="uk-nestable uk-margin-remove" v-el:mergefields-nestable>
                    <li v-for="mergefield in widget.data.merge_vars | orderBy 'widget_order'" class="uk-nestable-item" data-tag="{{ mergefield.tag }}">
                        <div class="uk-nestable-panel uk-visible-hover uk-form uk-flex uk-flex-middle">
                            <div class="uk-width-1-4">
                                <div class="uk-text-small" :class="{'uk-text-danger': !fieldTypeSupported(mergefield.field_type)}"
                                    :title="!fieldTypeSupported(mergefield.field_type) ? 'Fieldtype not supported' : ''">
                                    {{ mergefield.field_type }}
                                </div>
                                <strong>{{ mergefield.name }}</strong>
                                <em v-if="mergefield.req" class="uk-margin-small-left" :title="'Required' | trans">*</em><br>
                            </div>
                            <div class="uk-flex-item-1">
                                <label class="uk-form-label">{{ 'Label' | trans }}</label>
                                <div class="uk-form-controls">
                                    <input class="uk-form-width-medium" type="text" v-model="mergefield.label">
                                </div>
                            </div>
                            <div v-if="mergefield.choices">
                                <label class="uk-form-label">{{ 'Default value' | trans }}</label>
                                <div class="uk-form-controls">
                                    <select v-model="mergefield.value" class="uk-form-width-medium">
                                        <option v-for="choice in mergefield.choices" :value="choice">{{ choice }}</option>
                                    </select>
                                </div>
                            </div>
                            <label class="uk-margin-left"><input type="checkbox" v-model="mergefield.widget_show" class="uk-margin-small-right"
                                          :disabled="mergefield.req || !fieldTypeSupported(mergefield.field_type)"/>
                                {{ 'Show in widget' | trans }}
                            </label>
                            <ul class="uk-subnav pk-subnav-icon uk-margin-left">
                                <li><a class="pk-icon-move pk-icon-hover uk-nestable-handle"></a></li>
                            </ul>
                        </div>
                    </li>
                </ul>

            </div>
        </div>

    </div>

</template>

<script>


    export default {

        section: {
            label: 'List',
        },

        replace: false,

        props: {'widget': Object, 'config': Object, 'form': Object,},

        data: () => ({
            lists: [],
        }),

        watch: {
            'widget.data.list_id'(list_id) {
                this.loadMergeVars(list_id);
            },
        },

        created() {
            this.$options.partials = this.$parent.$options.partials;
            this.resource = this.$resource('api/mailchimp{/id}');
            this.$set('widget.data', _.merge({
                view: 'subscribe',
                list_id: '',
                merge_vars: []
            }, this.widget.data));
            this.loadLists();
            this.loadMergeVars(this.widget.data.list_id);
        },

        ready() {
            const vm = this;
            UIkit.nestable(this.$els.mergefieldsNestable, {
                maxDepth: 1,
                handleClass: 'uk-nestable-handle',
                group: 'mailchimp.mergefields'
            }).on('change.uk.nestable', (e, nestable, el, type) => {
                if (type && type !== 'removed') {

                    var mergefields = [];
                    _.forEach(nestable.list(), (item, idx) => {
                        var mergefield = _.find(vm.widget.data.merge_vars, 'tag', item.tag);
                        mergefield.widget_order = idx;
                        mergefields.push(mergefield);
                    });

                    vm.$set('widget.data.merge_vars', mergefields);

                }
            });
        },

        methods: {
            loadLists() {
                this.resource.query({id: 'list'})
                    .then(res =>this.lists = res.data.data, res => this.$notify(res.data, 'danger'))
            },

            loadMergeVars(list_id) {
                this.merge_vars = [];
                if (list_id) {
                    this.resource.query({id: 'merge_vars'}, {list_id,})
                        .then(res => this.mergeMergeVars(_.find(res.data.data, {id: list_id,}).merge_vars),
                            res => this.$notify(res.data, 'danger'))
                }
            },

            mergeMergeVars(merge_vars) {
                const mergefields = [];
                _.forEach(merge_vars, merge_var => {
                    const existing = _.find(this.widget.data.merge_vars, 'tag', merge_var.tag);
                    mergefields.push(_.merge(
                            merge_var,
                            {
                                widget_show: this.fieldTypeSupported(merge_var.field_type),
                                value: '',
                                label: merge_var.name,
                                widget_order: 0,
                            },
                            (existing || {})
                    ));
                });
                this.widget.data.merge_vars = mergefields;
            },

            fieldTypeSupported(field_type) {
                return ['text', 'email', 'number', 'radio', 'dropdown'].indexOf(field_type) > -1;
            },
        },

    };

</script>
