import template from './sw-cms-preview-text-image-text.html.twig';
import './sw-cms-preview-text-image-text.scss';


Shopware.Component.register('sw-cms-preview-text-image-text', {
    template,

    compatConfig: Shopware.compatConfig,

    computed: {
        assetFilter() {
            return Shopware.Filter.getByName('asset');
        },
    },
});
