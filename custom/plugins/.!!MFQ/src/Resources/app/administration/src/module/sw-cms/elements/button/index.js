
Shopware.Component.register('sw-cms-el-preview-button', () => import('./preview'));

Shopware.Component.register('sw-cms-el-config-button', () => import('./config'));

Shopware.Component.register('sw-cms-el-button', () => import('./component'));

Shopware.Service('cmsService').registerCmsElement({
    name: 'button',
    label: 'Button',
    component: 'sw-cms-el-button',
    configComponent: 'sw-cms-el-config-button',
    previewComponent: 'sw-cms-el-preview-button',
    defaultConfig: {
        title: {
            source: 'static',
            value: 'Click Me'
        },
        url: {
            source: 'static',
            value: 'https://example.com'
        }
    }
});