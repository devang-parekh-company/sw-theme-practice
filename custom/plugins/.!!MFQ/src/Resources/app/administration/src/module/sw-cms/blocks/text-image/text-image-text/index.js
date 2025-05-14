import CMS from '../../../constant/sw-cms.constant';

Shopware.Component.register('sw-cms-preview-text-image-text', () => import('./preview'));

Shopware.Component.register('sw-cms-block-text-image-text', () => import('./component'));

Shopware.Service('cmsService').registerCmsBlock({
    name: 'text-image-text',
    label: 'Text Image Text',
    category: 'text-image',
    component: 'sw-cms-block-text-image-text',
    previewComponent: 'sw-cms-preview-text-image-text',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        'left-text': {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h2 style="text-align: center;">Lorem Ipsum dolor sit amet</h2>
                        <p style="text-align: center;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                        `.trim(),
                    },
                },
            },
        },
        'center-image': {
            type: 'custom-image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                },
                data: {
                    media: {
                        value: null,
                        source: 'default',
                    },
                },
            },
        },
        'right-text': {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h2 style="text-align: center;">Lorem Ipsum dolor sit amet</h2>
                        <p style="text-align: center;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                        `.trim(),
                    },
                },
            },
        },

    }
});