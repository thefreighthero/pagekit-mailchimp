module.exports = [

    {
        entry: {
            'settings': './app/components/settings.vue',
            'widget-subscribe': './app/components/widget-subscribe.vue',
            'widget-mailchimp-subscribe': './app/views/widgets/subscribe.js',
        },
        output: {
            filename: './app/bundle/[name].js',
        },
        externals: {
            'lodash': '_',
            'jquery': 'jQuery',
            'uikit': 'UIkit',
            'vue': 'Vue',
        },
        module: {
            loaders: [
                {test: /\.vue$/, loader: 'vue',},
                {test: /\.html$/, loader: 'vue-html',},
                {test: /\.js/, loader: 'babel', query: {presets: ['es2015',],},},
            ],
        },

    },

];
