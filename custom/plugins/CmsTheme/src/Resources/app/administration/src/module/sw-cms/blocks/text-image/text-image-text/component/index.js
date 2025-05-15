import template from './sw-cms-block-text-image-text.html.twig';
import './sw-cms-block-text-image-text.scss';

Shopware.Component.register('sw-cms-block-text-image-text', {
    template,

    compatConfig: Shopware.compatConfig,
});
