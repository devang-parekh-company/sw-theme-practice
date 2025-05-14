import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'ict-image',
    label: 'sw-cms.elements.ictImages.label',
    component: 'sw-cms-el-ict-image',
    configComponent: 'sw-cms-el-config-ict-image',
    previewComponent: 'sw-cms-el-preview-ict-image',
    defaultConfig: {
        buttonField: {
            source: 'static',
            value: null,
        },
        media: {
            source: 'static',
            value: null,
            required: true,
            entity: {
                name: 'media',
            },
        },
        displayMode: {
            source: 'static',
            value: 'standard',
        },
        url: {
            source: 'static',
            value: null,
        },
        newTab: {
            source: 'static',
            value: false,
        },
        minHeight: {
            source: 'static',
            value: '340px',
        },
        verticalAlign: {
            source: 'static',
            value: null,
        },
    },
});
