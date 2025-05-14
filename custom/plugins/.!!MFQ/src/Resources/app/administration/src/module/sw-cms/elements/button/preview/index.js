import template from './sw-cms-el-preview-button.html.twig';

export default {
    template,

    props: {
        element: {
            type: Object,
            required: true
        }
    }
};
