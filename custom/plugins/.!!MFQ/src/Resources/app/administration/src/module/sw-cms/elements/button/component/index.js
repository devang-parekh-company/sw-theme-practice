import template from './sw-cms-el-button.html.twig';
const { Mixin } = Shopware;
export default {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    created() {
        this.createdComponent();
    },

    methods:{
        createdComponent() {
            this.initElementConfig('button');
        }
    },

    };
