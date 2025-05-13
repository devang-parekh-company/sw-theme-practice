import template from './sw-cms-preview-custom-image-text-gallery.html.twig';
import './sw-cms-preview-custom-image-text-gallery.scss';

Shopware.Component.register("sw-cms-preview-custom-image-text-gallery", {
    template,
    compatConfig: Shopware.compatConfig,
    computed: {
        assetFilter() {
            return Shopware.Filter.getByName('asset');
        },
    },
});
