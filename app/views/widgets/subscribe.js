module.exports = {

    data: function () {
        return _.merge({
            loading: false,
            mergefields: [],
            result: '',
            message: '',
            name: '',
            email: '',
            list_id: ''
        });
    },

    ready: function () {
        this.resource = this.$resource('api/mailchimp{/id}');
        this.list_id = this.$el.getAttribute('data-list_id');
    },

    computed: {
        merge_vars: function () {
            var merge_vars = {};
            this.mergefields.forEach(function (mergefield) {
                if (!mergefield.widget_show || mergefield.field_type === 'email') {
                    return;
                }
                merge_vars[mergefield.tag] = mergefield.value;
            });
            return merge_vars;
        },
        email: function () {
            var mailfield = _.find(this.mergefields, 'field_type', 'email');
            return mailfield ? mailfield.value : '';
        }
    },

    methods: {
        setMergefields: function (mergefields) {
            this.mergefields = mergefields;
        },

        submit: function () {
            this.loading = true;
            this.result = '';
            this.message = '';
            this.resource.save({id: 'subscribe'}, {
                list_id: this.list_id,
                email: this.email,
                merge_vars: this.merge_vars
            }).then(function (res) {
                this.result = res.data.result;
                this.message = res.data.message;
                this.loading = false;
            }, function (res) {
                this.result = 'fail';
                this.message = res.data;
                this.loading = false;
            });
        }
    }
};
