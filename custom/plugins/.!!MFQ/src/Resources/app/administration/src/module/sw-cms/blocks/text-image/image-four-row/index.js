import CMS from '../../../constant/sw-cms.constant';

Shopware.Component.register('sw-cms-preview-image-four-row', () => import('./preview'));

Shopware.Component.register('sw-cms-block-image-four-row', () => import('./component'));

Shopware.Service('cmsService').registerCmsBlock({
    name: 'image-four-row',
    label: 'Image Four Row',
    category: 'text-image',
    component: 'sw-cms-block-image-four-row',
    previewComponent: 'sw-cms-preview-image-four-row',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        'left-button':{
            type: 'button',
            default: {
                config: {
                    title: {
                        source: 'static',
                        value: 'Click Me'
                    },
                    url: {
                        source: 'static',
                        value: 'https://example.com'
                    }
                }
            }
        },
        'center-left-button':{
            type: 'button',
            default: {
                config: {
                    title: {
                        source: 'static',
                        value: 'Click Me'
                    },
                    url: {
                        source: 'static',
                        value: 'https://example.com'
                    }
                }
            }
        },

        'center-right-button':{
            type: 'button',
            default: {
                config: {
                    title: {
                        source: 'static',
                        value: 'Click Me'
                    },
                    url: {
                        source: 'static',
                        value: 'https://example.com'
                    }
                }
            }
        },

        'right-button':{
            type: 'button',
            default: {
                config: {
                    title: {
                        source: 'static',
                        value: 'Click Me'
                    },
                    url: {
                        source: 'static',
                        value: 'https://example.com'
                    }
                }
            }
        },

        'left-image': {
            type: 'image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewCamera,
                        source: 'default',
                    },
                },
            },
        },
        'center-left-image': {
            type: 'image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewPlant,
                        source: 'default',
                    },
                },
            },
        },
        'center-right-image': {
            type: 'image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewGlasses,
                        source: 'default',
                    },
                },
            },
        },
        'right-image': {
            type: 'image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewMountain,
                        source: 'default',
                    },
                },
            },
        },
        'left-text': {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                        `.trim(),
                    },
                },
            },
        },
        'center-left-text': {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                        `.trim(),
                    },
                },
            },
        },
        'center-right-text': {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                        `.trim(),
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
                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                        `.trim(),
                    },
                },
            },
        },
    },
});