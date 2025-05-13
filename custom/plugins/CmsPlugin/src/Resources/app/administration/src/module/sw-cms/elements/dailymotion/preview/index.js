import template from './sw-cms-el-preview-dailymotion.html.twig';
import './sw-cms-el-preview-dailymotion.scss';

Shopware.Component.register('sw-cms-el-preview-dailymotion', {
    template,
    computed: {
        assetFilter() {
            return Shopware.Filter.getByName('asset');
        },
    },
});
