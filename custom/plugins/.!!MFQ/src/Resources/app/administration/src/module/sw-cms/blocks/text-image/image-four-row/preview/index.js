import template from './sw-cms-preview-image-four-row.html.twig';
import './sw-cms-preview-image-four-row.scss';

export default {
    template,

    computed: {
        assetFilter() {
            return Shopware.Filter.getByName('asset');
        },
    },
};
