module.exports = {

    data() {
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

    ready() {
        this.resource = this.$resource('api/mailchimp{/id}');
        this.list_id = this.$el.getAttribute('data-list_id');
    },

    computed: {
        merge_vars() {
            var merge_vars = {};
            this.mergefields.forEach(mergefield => {
                if (!mergefield.widget_show || mergefield.field_type === 'email') {
                    return;
                }
                merge_vars[mergefield.tag] = mergefield.value;
            });
            return merge_vars;
        },
        email() {
            var mailfield = _.find(this.mergefields, 'field_type', 'email');
            return mailfield ? mailfield.value : '';
        }
    },

    methods: {
        setMergefields(mergefields) {
            this.mergefields = mergefields;
        },

        submit() {
            if (!this.email) {
                this.result = 'fail';
                this.message = this.$trans('Please enter a valid email address');
                return;
            }
            this.loading = true;
            this.result = '';
            this.message = '';
            this.resource.save({id: 'subscribe'}, {
                list_id: this.list_id,
                email: this.email,
                merge_vars: this.merge_vars
            }).then(res => {
                this.result = res.data.result;
                this.message = res.data.message;
                this.loading = false;
            }, res => {
                this.result = 'fail';
                this.message = res.data;
                this.loading = false;
            });
        }
    }
};
