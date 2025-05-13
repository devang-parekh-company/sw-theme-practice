import template from './sw-cms-el-config-buy-button.html.twig';
import './sw-cms-el-config-buy-button.scss';

const { Mixin } = Shopware;

Shopware.Component.register("sw-cms-el-config-buy-button", {

    template,

    compatConfig: Shopware.compatConfig,

    emits: ['element-update'],

    mixins: [
        Mixin.getByName('cms-element'),
    ],

    data() {
        return {
            buttonText: '',
            redirectLink: ''
        };
    },

    computed: {
        isProductPage() {
            return this.cmsPageState?.currentPage?.type === 'product_detail';
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('buy-button');
            if (this.element.config.buttonText) {
                this.buttonText = this.element.config.buttonText.value;
            }
            if (this.element.config.redirectLink) {
                this.redirectLink = this.element.config.redirectLink.value;
            }
        },

        onButtonTextChange() {
            this.element.config.buttonText.value = this.buttonText;
            console.log("this.element.config.buttonText", this.element.config.buttonText);
            
            // this.$emit('element-update', this.element);
        },

        onRedirectLinkChange() {
            this.element.config.redirectLink.value = this.redirectLink;
            // this.$emit('element-update', this.element);
            console.log("this.element.config.redirectLink", this.element.config.redirectLink);

        }
    },
});
