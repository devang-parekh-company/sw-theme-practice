import template from './sw-cms-preview-text-image-text.html.twig';
import './sw-cms-preview-text-image-text.scss';

export default {
    template,

    computed: {
        assetFilter() {
            return Shopware.Filter.getByName('asset');
        },
    },
};
